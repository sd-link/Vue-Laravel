<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class LarapressModuleDisableCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'larapress:module:disable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Disable a module for Larapress CMS';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $slug = $this->argument('slug');

        if ($this->laravel['modules']->isEnabled($slug)) {
            $this->laravel['modules']->disable($slug);

            $module = $this->laravel['modules']->where('slug', $slug);

            event($slug.'.module.disabled', [$module, null]);

            $this->info('Module was disabled successfully.');
        } else {
            $this->comment('Module is already disabled.');
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
