<?php


namespace App\Console\Commands;


use Discuz\Console\AbstractCommand;
use Discuz\Database\Migrator;
use Discuz\Foundation\Application;
use Symfony\Component\Console\Input\InputOption;

class MigrateCommand extends AbstractCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the database migrations';

    protected $migrator;

    protected $app;

    public function __construct(Migrator $migrator, Application $app)
    {
        parent::__construct();
        $this->migrator = $migrator;
        $this->app = $app;
    }

    public function configure()
    {
        $this->addOption('database', null, InputOption::VALUE_OPTIONAL);
        parent::configure();
    }


    protected function handle()
    {
        $this->prepareDatabase();

        $this->migrator->setOutput($this->output)
            ->run($this->getMigrationPaths());
    }

    protected function getMigrationPaths()
    {
        return [$this->app->basePath('migrations')];
    }

    /**
     * Prepare the migration database for running.
     *
     * @return void
     */
    protected function prepareDatabase()
    {
        $this->migrator->setConnection($this->input->getOption('database'));
        if (! $this->migrator->repositoryExists()) {
            $this->call('migrate:install', array_filter([
                '--database' => $this->option('database'),
            ]));
        }
    }
}
