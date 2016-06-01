<?php namespace Keyhunter\Administrator\Console;

class AdministratorPage extends AdministratorGenerator {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'administrator:page';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create new administration module.';

	protected $defaultModel = "App\\User";

	/**
	 * Get the stub file for the generator.
	 *
	 * @return string
	 */
	protected function getStub()
	{
		return __DIR__.'/stubs/page.stub';
	}

	/**
	 * @return mixed
	 */
	protected function getConfigPath()
	{
		return $this->laravel->make('scaffold.config')['models_path'];
	}
}
