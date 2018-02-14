<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class LarapressModuleEnableCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'larapress:module:enable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enable a module for Larapress CMS';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $slug = $this->argument('slug');

        if ($this->laravel['modules']->isDisabled($slug)) {
            $this->laravel['modules']->enable($slug);

            $module = $this->laravel['modules']->where('slug', $slug);

            event($slug.'.module.enabled', [$module, null]);

            $this->info('Module was enabled successfully.');
        } else {
            $this->comment('Module is already enabled.');
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['slug', InputArgument::REQUIRED, 'Module slug.'],
        ];
    }
}
