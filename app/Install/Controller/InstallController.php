<?php

namespace App\Install\Controller;

use App\Console\Commands\KeyGenerate;
use App\Console\Commands\RsaCertGenerate;
use Discuz\Console\Kernel;
use Discuz\Foundation\Application;
use Illuminate\Database\Console\Migrations\MigrateCommand;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use PDO;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RangeException;
use SplStack;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\NullOutput;
use Zend\Diactoros\Response\EmptyResponse;
use Zend\Diactoros\Response\JsonResponse;

class InstallController implements RequestHandlerInterface
{
    protected $app;
    protected $keyGenerate;
    protected $certGenerate;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @inheritDoc
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $input = $request->getParsedBody();

        //创建数据库
        $this->installDatabase($input);
        //创建配置文件
        $this->installConfig($input);
        //生成站点唯一key
        $this->installKeyAndCertGenerate();
        //初始化数据
        $this->installInitData($input);

        return new EmptyResponse();
    }

    private function installDatabase($input) {
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
        $this->app['config']->set('database.connections',[
                'mysql' => $mysqlConfig,
                'discuz_mysql' => array_merge($mysqlConfig, ['database' => Arr::get($input, 'mysqlDatabase')])
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

    private function installConfig($input) {
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
        ], [
            $host,
            $port,
            Arr::get($input, 'mysqlDatabase'),
            Arr::get($input, 'mysqlUsername'),
            Arr::get($input, 'mysqlPassword'),
            Arr::get($input, 'tablePrefix', ''),
        ], $defaultConfigFile);

        file_put_contents($this->app->configPath('config.php'), $stub);
    }

    private function installKeyAndCertGenerate() {
        $input = new ArrayInput([]);
        $output = new BufferedOutput();
        //站点唯一key
        $this->app->make(KeyGenerate::class)->run($input, $output);
        //证书
        $this->app->make(RsaCertGenerate::class)->run($input, $output);
    }

    private function installInitData($input)
    {
        $console = $this->app->make(Kernel::class);



//        $this->app['config']->set('database.connections.mysql.database', Arr::get($input, 'mysqlDatabase'));
//        $this->app['db']->reconnection('mysql');

//        dd($this->app['config']->get('database'));

        $aaa = $console->call('migrate', ['--force' => true, '--database' => 'discuz_mysql']);

        dd($aaa);
    }
}
