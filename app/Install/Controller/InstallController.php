<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Install\Controller;

use App\Console\Commands\KeyGenerate;
use App\Console\Commands\RsaCertGenerate;
use App\Models\Group;
use App\Models\User;
use App\Passport\Entities\AccessTokenEntity;
use App\Passport\Entities\ClientEntity;
use App\Passport\Repositories\AccessTokenRepository;
use App\Settings\SettingsRepository;
use DateInterval;
use DateTimeImmutable;
use Discuz\Api\Client;
use Discuz\Console\Kernel;
use Discuz\Foundation\Application;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use League\OAuth2\Server\CryptKey;
use PDO;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RangeException;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;

class InstallController implements RequestHandlerInterface
{
    protected $app;

    protected $input;

    protected $output;

    protected $console;

    protected $apiClient;

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

        try {
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
            $this->installSiteName($input);
            //创建管理员用户
            $this->installAdminUser($input);
            //auto login Admin user
            $token = $this->installAutoLogin($input);
        } catch (Exception $e) {
            return new HtmlResponse($e->getMessage(), 500);
        }


        return new JsonResponse([
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
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ];


        $db = $this->app->make('db');
        $this->app['config']->set(
            'database.connections',
            [
                'mysql' => $mysqlConfig,
                'discuz_mysql' => array_merge($mysqlConfig, [
                    'database' => Arr::get($input, 'mysqlDatabase'),
                    'prefix' => Arr::get($input, 'tablePrefix')
                ])
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

        $pdo->query('CREATE DATABASE IF NOT EXISTS '.Arr::get($input, 'mysqlDatabase'))->execute();
    }

    private function installConfig($input)
    {
        $host = Arr::get($input, 'mysqlHost');
        $port = 3306;

        $defaultConfigFile = file_get_contents($this->app->configPath('config_default.php'));

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
            Arr::get($input, 'siteUrl'),
        ], $defaultConfigFile);

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
    }

    private function installInitMigrate()
    {
        $this->getConsole()->call('migrate', ['--force' => true, '--database' => 'discuz_mysql']);
    }

    private function installInItData()
    {
        $this->getConsole()->call('db:seed', ['--force' => true, '--database' => 'discuz_mysql']);
    }

    private function installSiteName($input)
    {
        $this->app->make(SettingsRepository::class)->set('site_name', Arr::get($input, 'forumTitle'));
        $this->app->make(SettingsRepository::class)->set('site_url', Arr::get($input, 'siteUrl'));
    }

    private function installAdminUser(&$input)
    {
        if ($input['adminPasswordConfirmation'] !== $input['adminPassword']) {
            throw new Exception('管理员两次密码不一致');
        }

        $user = new User();
        $user->truncate();
        $user->username = Arr::get($input, 'adminUsername');
        $user->password = Arr::get($input, 'adminPassword');
        $user->last_login_ip = Arr::get($input, 'ip');
        $user->register_ip = Arr::get($input, 'ip');
        $user->save();
        $input['user_id'] = $user->id;

        $user->groups()->sync(Group::ADMINISTRATOR_ID);
    }

    private function installAutoLogin($input)
    {
        $token = new AccessTokenEntity();

        $token->setPrivateKey(new CryptKey(storage_path('cert/private.key')));
        $token->setClient(new ClientEntity());
        $token->setIdentifier($input['user_id']);
        $token->setExpiryDateTime((new DateTimeImmutable())->add(new DateInterval(AccessTokenRepository::TOKEN_EXP)));

        return $token->__toString();
    }

    private function getConsole()
    {
        return $this->console ?? $console = $this->app->make(Kernel::class);
    }
}
