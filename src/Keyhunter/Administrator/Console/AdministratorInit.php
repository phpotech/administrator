<?php namespace Keyhunter\Administrator\Console;

use Artisan;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
//use Illuminate\Foundation\Composer; // < 5.2
use Illuminate\Support\Composer; // > 5.2
use Keyhunter\Administrator\AdministratorServiceProvider;
use Keyhunter\Multilingual\MultilingualServiceProvider;

class AdministratorInit extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = '
        administrator:init
        {--no-publish : Do not publish configs and assets}
        {--no-tables : Do not create boilerplate tables}
        {--no-auth : Do not ask for authentication scheme}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Init administration module.';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * @var \Illuminate\Foundation\Composer
     */
    protected $composer;

    /**
     * @var array
     */
    protected $stubs;

    /**
     * Check if languages migration already has been created
     *
     * @var boolean
     */
    protected $languagesMigrated = false;

    /**
     * Create a new queue job table command instance.
     *
     * @param \Illuminate\Filesystem\Filesystem|Filesystem $files
     * @param Composer                                     $composer
     */
    public function __construct(Filesystem $files, Composer $composer)
    {
        parent::__construct();

        $this->files = $files;
        $this->composer = $composer;
        $this->stubs = [];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (! $this->option('no-publish')) {
            $this->call('vendor:publish', ['--provider' => AdministratorServiceProvider::class]);
        }

        // Create default migrations
        if (! $this->option('no-tables')) {
            $this->createDefaultMigrations();
        }

        if (! $this->option('no-auth')) {
            $this->createAuthMigration();
        }

        $this->buildMigrations();

        $this->composer->dumpAutoloads();

        if (! empty($this->stubs)) {
            if ("yes" == $this->choice("Run migrations?", ['yes', 'no'], 'no')) {
                $this->call("migrate");
            } else {
                $this->comment("Migrations created.");
                $this->comment('To apply run: "php artisan migrate"');
            }
        }
    }

    /**
     * Create a base migration file for the table.
     *
     * @param $name Migration name
     * @return string
     */
    protected function createBaseMigration($name)
    {
        $path = $this->laravel->databasePath() . '/migrations';

        return $this->laravel['migration.creator']->create($name, $path);
    }

    /**
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function createDefaultMigrations()
    {
        $tables = [
            'options' => 0,
            'pages'   => 1
        ];

        $this->createLanguagesMigration();

        foreach ($tables as $table => $mui) {
            if ("yes" == $this->choice("Create '{$table}' table?", [1 => 'yes', 'no'], 1)) {
                if ($mui && 'yes' == $this->choice("Should '{$table}' be translatable?", [1 => 'yes', 'no'], 1)) {
                    $this->createLanguagesMigration();

                    $this->stubs[] = "translatable/create_{$table}_table";
                } else {
                    $this->stubs[] = "create_{$table}_table";;
                }
            }
        }
    }

    protected function createAuthMigration()
    {
        $this->info(<<<SYNOPSYS
Admin authentication scheme:
    role-based: Administrators are stored in users table with specific role value: admin|member.
    admins-table: Administrators are stored in separated "admins" table.
    no-action: Leave default Auth scheme unchanged
SYNOPSYS
        );
        switch ($authScheme = $this->choice("Choose approprieated scheme", [1 => 'role-based', 'admins-table', 'no-action'], 1)) {
            case 'role-based':
                $this->stubs[] = 'add_role_to_users_table';
                break;

            case 'admins-table':
                $this->stubs[] = 'create_admins_table';
                break;
        }
    }

    private function buildMigrations()
    {
        foreach ($this->stubs as $stub) {
            $pathParts = explode('/', $stub);
            $stub = array_pop($pathParts);

            $fullPath = $this->createBaseMigration($stub);

            $this->info("Migration {$fullPath} created");

            $stub = $this->resolveStubLocation($pathParts, $stub);

            $stub = $this->files->get(__DIR__ . '/stubs/' . $stub . '.stub');

            $this->files->put($fullPath, $stub);
        }
    }

    private function createLanguagesMigration()
    {
        if ($this->languagesMigrated) {
            return false;
        }

        // Make sure that languages table was not created before
        if ('yes' == $this->ask("Create languages table?", 'yes')) {
            $this->call('languages:table');
            $this->call('vendor:publish', ['provider' => MultilingualServiceProvider::class]);
        }

        $this->languagesMigrated = true;
    }

    /**
     * @param $pathParts
     * @param $stub
     * @return string
     */
    protected function resolveStubLocation($pathParts, $stub)
    {
        $subFolder = '';
        if (count($pathParts)) {
            $subFolder = trim(join('/', $pathParts), '/') . '/'; // translatable
        }
        $stub = $subFolder . $stub;

        return $stub;
    }
}
