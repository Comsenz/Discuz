<?php


namespace App\Console\Commands;


use Discuz\Console\AbstractCommand;
use Discuz\Foundation\Application;
use Illuminate\Encryption\Encrypter;

class KeyGenerate extends AbstractCommand
{

    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;

        parent::__construct();
    }

    /**
     * 命令行的名称及签名。
     *
     * @var string
     */
    protected $signature = 'key:generate';

    /**
     * 命令行的描述
     *
     * @var string
     */
    protected $description = '生成站点唯一key，用于HASH';

    /**
     * Fire the command.
     */
    protected function handle()
    {
        $key = $this->generateRandomKey();

        file_put_contents(base_path('config/config.php'), preg_replace('/\'key\'.*,\n/m', "'key' => '{$key}',\n", file_get_contents(base_path('config/config.php'))));

        $this->info('站点唯一key为：'.$key);
    }

    /**
     * Generate a random key for the application.
     *
     * @return string
     */
    protected function generateRandomKey()
    {
        return 'base64:'.base64_encode(
                Encrypter::generateKey($this->app->config('cipher'))
            );
    }
}
