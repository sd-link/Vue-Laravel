<?php

namespace Caffeinated\Modules\Console\Commands;

use Illuminate\Console\Command;

class ModuleOptimizeCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'Larapress:module:optimize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize the module cache for better performance in Larapress CMS';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->info('Generating optimized module cache');

        $this->laravel['modules']->optimize();

        event('modules.optimized', [$this->laravel['modules']->all()]);
    }
}
