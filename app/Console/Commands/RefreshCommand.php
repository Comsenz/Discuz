<?php


namespace App\Console\Commands;


use Discuz\Console\AbstractCommand;

class RefreshCommand extends AbstractCommand
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'migrate:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset and re-run all migrations';


    protected function configure()
    {
        parent::configure();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {

        $this->runReset();

        // The refresh command is essentially just a brief aggregate of a few other of
        // the migration commands and just provides a convenient wrapper to execute
        // them in succession. We'll also see if we need to re-seed the database.
        $this->call('migrate');
    }

    /**
     * Run the reset command.
     *
     * @param  string  $database
     * @param  string  $path
     * @return void
     */
    protected function runReset()
    {
        $this->call('migrate:reset');
    }
}

