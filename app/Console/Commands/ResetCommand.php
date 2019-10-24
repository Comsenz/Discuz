<?php


namespace App\Console\Commands;


use Discuz\Console\AbstractCommand;
use Discuz\Database\Migrator;
use Discuz\Foundation\Application;

class ResetCommand extends AbstractCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'migrate:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback all database migrations';

    /**
     * The migrator instance.
     *
     * @var \Illuminate\Database\Migrations\Migrator
     */
    protected $migrator;

    protected $app;


    /**
     * ResetCommand constructor.
     * @param Migrator $migrator
     * @param Application $app
     */
    public function __construct(Migrator $migrator, Application $app)
    {
        $this->migrator = $migrator;
        $this->app = $app;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {

        // First, we'll make sure that the migration table actually exists before we
        // start trying to rollback and re-run all of the migrations. If it's not
        // present we'll just bail out with an info message for the developers.
        if (! $this->migrator->repositoryExists()) {
            $this->info('Migration table not found.');
        }

        $this->migrator->setOutput($this->output)->reset(
            $this->getMigrationPaths()
        );
    }

    protected function getMigrationPaths()
    {
        return [$this->app->basePath('migrations')];
    }
}

