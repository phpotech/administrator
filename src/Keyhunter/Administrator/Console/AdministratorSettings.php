<?php namespace Keyhunter\Administrator\Console;

class AdministratorSettings extends AdministratorGenerator {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'administrator:settings';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create new administration settings page.';

	protected $defaultModel = "Keyhunter\\Administrator\\Model\\Settings";

	/**
	 * Get the stub file for the generator.
	 *
	 * @return string
	 */
	protected function getStub()
	{
		return __DIR__.'/stubs/settings.stub';
	}

	/**
	 * @return mixed
	 */
	protected function getConfigPath()
	{
		return $this->laravel->make('scaffold.config')['settings_path'];
	}
}
