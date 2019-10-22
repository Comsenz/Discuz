<?php


namespace App\Console\Commands;

use Discuz\Console\AbstractCommand;
use Discuz\Database\MigrationCreator;
use Discuz\Foundation\Application;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class MigrateMakeCommand extends AbstractCommand
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'make:migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new migration file';

    /**
     * The migration creator instance.
     *
     * @var \Illuminate\Database\Migrations\MigrationCreator
     */
    protected $creator;


    protected $app;

    public function __construct(MigrationCreator $creator, Application $app)
    {
        parent::__construct();

        $this->creator = $creator;
        $this->app = $app;

    }

    protected function configure()
    {
        $this->addArgument('name')
            ->addOption('create')
            ->addOption('table', 'table name', InputOption::VALUE_REQUIRED);
        parent::configure();
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        // It's possible for the developer to specify the tables to modify in this
        // schema operation. The developer may also specify if this table needs
        // to be freshly created so we can create the appropriate migrations.
        $name = Str::snake(trim($this->input->getArgument('name')));

        $create = $this->input->getOption('create') ?: false;
        $table = $this->input->getOption('table');

        // Now we are ready to write the migration out to disk. Once we've written
        // the migration out, we will dump-autoload for the entire framework to
        // make sure that the migrations are registered by the class loaders.
        $this->writeMigration($name, $table, $create);
    }


    /**
     * @param $name
     * @param $table
     * @param $create
     * @throws \Exception
     */
    protected function writeMigration($name, $table, $create)
    {
        $file = $this->creator->create(
            $name, $this->getMigrationPath(), $table, $create
        );

        $file = pathinfo($file, PATHINFO_FILENAME);

        $this->info("Created Migration: {$file}");
    }

    /**
     * Get the path to the migration directory.
     *
     * @return string
     */
    protected function getMigrationPath()
    {
        return $this->app->basePath('migrations');
    }
}
