<?php namespace Keyhunter\Administrator\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

abstract class AdministratorGenerator extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description;

    /**
     * @var
     */
    private $files;

    protected $defaultModel;

    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->validateFilename();

        $name = strtolower($this->argument('name'));

        $path = $this->getConfigPath() . '/' . $name . '.php';

        if ($this->files->exists($path) && ! $this->overwrite($name)) {
            exit;
        }

        $stub = $this->files->get($this->getStub());

        $stub = $this->replaceThings($stub);

        $this->files->put($path, $stub);

        $this->info("Page {$name}.php was created!");
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'Page name.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['--model', "m", InputOption::VALUE_OPTIONAL, 'Eloquent model.', $this->defaultModel],
        ];
    }

    private function replaceThings($stub)
    {
        $stub = str_replace('{{title}}', $this->argument('name'), $stub);

        $stub = str_replace('{{model}}', $this->option('model'), $stub);

        return $stub;
    }

    private function validateFilename()
    {
        if (! preg_match('~^[a-z0-9\_]+$~si', $name = $this->argument('name'))) {
            $this->error("Name should be a valid file name");
            exit;
        }
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    abstract protected function getStub();

    /**
     * @return mixed
     */
    abstract protected function getConfigPath();

    /**
     * @param $name
     * @return bool
     */
    protected function overwrite($name)
    {
        return $this->confirm("File {$name}.php already exists. Overwrite? [y/n]", 'y');
    }
}
