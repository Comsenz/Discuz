<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Install\Controller;

use App\Console\Commands\KeyGenerate;
use App\Console\Commands\RsaCertGenerate;
use App\Console\Commands\StorageLinkCommand;
use App\Models\Group;
use App\Models\User;
use App\Models\UserWallet;
use App\Passport\Entities\AccessTokenEntity;
use App\Passport\Entities\ClientEntity;
use App\Passport\Repositories\AccessTokenRepository;
use App\Settings\SettingsRepository;
use DateInterval;
use DateTimeImmutable;
use Discuz\Api\Client;
use Discuz\Console\Kernel;
use Discuz\Foundation\Application;
use Discuz\Http\DiscuzResponseFactory;
use Discuz\Qcloud\QcloudTrait;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use League\OAuth2\Server\CryptKey;
use PDO;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RangeException;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class InstallController implements RequestHandlerInterface
{
    use QcloudTrait;

    protected $app;

    protected $input;

    protected $output;

    protected $console;

    protected $apiClient;

    protected $setting;

    public function __construct(Application $app, Client $apiClient)
    {
        $this->app = $app;
        $this->apiClient = $apiClient;
    }

    /**
     * @inheritDoc
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $input = $request->getParsedBody();
        $input['ip'] = Arr::get($request->getServerParams(), 'REMOTE_ADDR', '127.0.0.1');
        $input['site_url'] = $request->getUri()->getScheme() . '://' . $request->getUri()->getHost();

        if ($this->app->isInstall()) {
            return DiscuzResponseFactory::HtmlResponse('已安装', 500);
        }

        $tablePrefix = Arr::get($input, 'tablePrefix', null);
        if ($tablePrefix && !preg_match("/^\w+$/", $tablePrefix)) {
            return DiscuzResponseFactory::HtmlResponse('表前缀格式错误', 500);
        }

        try {
            $this->dropConfigFile();
            //创建数据库
            $this->installDatabase($input);
            //创建配置文件
            $this->installConfig($input);
            //生成站点唯一key和jwt cert
            $this->installKeyAndCertGenerate();
            //初始化表
            $this->installInitMigrate();
            //初始化默认数据
            $this->installInItData();
            //站点名称设置
            $this->installSiteSetting($input);
            //创建管理员用户
            $this->installAdminUser($input);
            //auto login Admin user
            $token = $this->installAutoLogin($input);
            //上报
            $this->cloudReport($input);
            //安装成功
            touch($this->app->storagePath().'/install.lock');
        } catch (Exception $e) {
            $this->dropConfigFile();
            return DiscuzResponseFactory::HtmlResponse($e->getMessage(), 500);
        }

        return DiscuzResponseFactory::JsonResponse([
            'token' => $token
        ]);
    }

    private function installDatabase($input)
    {
        $host = Arr::get($input, 'mysqlHost');
        $port = 3306;

        $mysqlConfig = [
            'driver' => 'mysql',
            'host' => $host,
            'port' => $port,
            'database' => '',
            'username' => Arr::get($input, 'mysqlUsername'),
            'password' => Arr::get($input, 'mysqlPassword'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => Arr::get($input, 'tablePrefix', ''),
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => 'InnoDB',
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => '',
            ]) : [],
        ];
        $db = $this->app->make('db');
        $this->app['config']->set(
            'database.connections',
            [
                'mysql' => $mysqlConfig
            ]
        );

        $pdo = $db->connection('mysql')->getPdo();

        $version = $pdo->query('SELECT VERSION()')->fetchColumn();

        if (Str::contains($version, 'MariaDB')) {
            if (version_compare($version, '10.0.5', '<')) {
                throw new RangeException('MariaDB version too low. You need at least MariaDB 10.0.5');
            }
        } else {
            if (version_compare($version, '5.6.0', '<')) {
                throw new RangeException('MySQL version too low. You need at least MySQL 5.6.');
            }
        }

        $pdo->query('CREATE DATABASE IF NOT EXISTS '.Arr::get($input, 'mysqlDatabase').' DEFAULT CHARACTER SET = `utf8mb4` DEFAULT COLLATE = `utf8mb4_unicode_ci`')->execute();

        $this->app['config']->set(
            'database.connections',
            [
                'mysql' => array_merge($mysqlConfig, [
                    'database' => Arr::get($input, 'mysqlDatabase'),
                ])
            ]
        );
        $db->reconnect('mysql');
    }

    private function installConfig($input)
    {
        $host = Arr::get($input, 'mysqlHost');
        $port = 3306;

        $defaultConfig = file_get_contents($this->app->configPath('config_default.php'));

        if (Str::contains($host, ':')) {
            list($host, $port) = explode(':', $host, 2);
        }

        $stub = str_replace([
            'DummyDbHost',
            'DummyDbPort',
            'DummyDbDatabase',
            'DummyDbUsername',
            'DummyDbPassword',
            'DummyDbPrefix',
            'DummySiteUrl',
        ], [
            $host,
            $port,
            Arr::get($input, 'mysqlDatabase'),
            Arr::get($input, 'mysqlUsername'),
            Arr::get($input, 'mysqlPassword'),
            Arr::get($input, 'tablePrefix', ''),
            Arr::get($input, 'site_url'),
        ], $defaultConfig);

        file_put_contents($this->app->configPath('config.php'), $stub);
    }

    private function installKeyAndCertGenerate()
    {
        $this->input = new ArrayInput([]);
        $this->output = new BufferedOutput();
        //站点唯一key
        $this->app->make(KeyGenerate::class)->run($this->input, $this->output);
        //证书
        $this->app->make(RsaCertGenerate::class)->run($this->input, $this->output);
        //软连
        $this->app->make(StorageLinkCommand::class)->run($this->input, $this->output);
    }

    private function installInitMigrate()
    {
        $this->getConsole()->call('migrate', ['--force' => true]);
    }

    private function installInItData()
    {
        $this->getConsole()->call('db:seed', ['--force' => true]);
    }

    private function installSiteSetting($input)
    {
        $this->setting = $this->app->make(SettingsRepository::class);

        $this->setting->set('site_name', Arr::get($input, 'forumTitle'));
        $this->setting->set('site_url', Arr::get($input, 'site_url'));
        $this->setting->set('site_install', Carbon::now());
    }

    private function installAdminUser(&$input)
    {
        if ($input['adminPasswordConfirmation'] !== $input['adminPassword']) {
            throw new Exception('管理员两次密码不一致');
        }

        $user = new User();
        $userWallet = new UserWallet();
        $this->app['db']->statement('SET FOREIGN_KEY_CHECKS=0;');
        $user->truncate();
        $userWallet->truncate();
        $this->app['db']->statement('SET FOREIGN_KEY_CHECKS=1;');
        $user->username = Arr::get($input, 'adminUsername');
        $user->password = Arr::get($input, 'adminPassword');
        $user->last_login_ip = Arr::get($input, 'ip');
        $user->register_ip = Arr::get($input, 'ip');
        $user->save();
        $input['user_id'] = $user->id;

        //生成钱包
        UserWallet::createUserWallet($user->id);

        $user->groups()->sync(Group::ADMINISTRATOR_ID);
    }

    private function installAutoLogin($input)
    {
        $token = new AccessTokenEntity();

        $token->setPrivateKey(new CryptKey(storage_path('cert/private.key'), '', false));
        $token->setClient(new ClientEntity());
        $token->setIdentifier($input['user_id']);
        $token->setExpiryDateTime((new DateTimeImmutable())->add(new DateInterval(AccessTokenRepository::TOKEN_EXP)));

        return $token->__toString();
    }

    protected function cloudReport($input)
    {
        try {
            $this->report(['url' => $input['site_url']])->then(function (ResponseInterface $response) {
                $data = json_decode($response->getBody()->getContents(), true);
                $this->setting->set('site_id', Arr::get($data, 'site_id'));
                $this->setting->set('site_secret', Arr::get($data, 'site_secret'));
            })->wait();
        } catch (Exception $e) {
        }
    }

    private function getConsole()
    {
        return $this->console ?? $console = $this->app->make(Kernel::class);
    }

    protected function dropConfigFile()
    {
        $configFile = $this->app->basePath('config/config.php');
        file_exists($configFile) && unlink($configFile);
    }
}
