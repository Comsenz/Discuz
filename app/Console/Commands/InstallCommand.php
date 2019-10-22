<?php


namespace App\Console\Commands;


use Discuz\Console\AbstractCommand;
use Illuminate\Database\Migrations\MigrationRepositoryInterface;
use Symfony\Component\Console\Input\InputOption;
class InstallCommand extends AbstractCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'migrate:install';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the migration repository';
    /**
     * The repository instance.
     *
     * @var \Illuminate\Database\Migrations\MigrationRepositoryInterface
     */
    protected $repository;
    /**
     * Create a new migration install command instance.
     *
     * @param  \Illuminate\Database\Migrations\MigrationRepositoryInterface  $repository
     * @return void
     */
    public function __construct(MigrationRepositoryInterface $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    public function configure()
    {
        $this->addOption('database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use');
        parent::configure();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->repository->setSource($this->input->getOption('database'));
        $this->repository->createRepository();
        $this->info('Migration table created successfully.');
    }
}
