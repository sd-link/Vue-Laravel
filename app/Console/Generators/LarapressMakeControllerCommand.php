<?php

namespace App\Console\Generators;

use Caffeinated\Modules\Console\GeneratorCommand;

class LarapressMakeControllerCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larapress:make:module:controller
    	{slug : The slug of the module}
    	{name : The name of the controller class}
    	{--resource : Generate a module resource controller class}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new module controller class for Larapress CMS';

    /**
     * String to store the command type.
     *
     * @var string
     */
    protected $type = 'Module controller';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if ($this->option('resource')) {
            return __DIR__ . '/stubs/controller.resource.stub';
        }

        return __DIR__ . '/stubs/controller.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return module_class($this->argument('slug'), 'Http\\Controllers');
    }
}
