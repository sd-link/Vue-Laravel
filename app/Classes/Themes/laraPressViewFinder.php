<?php

namespace App\Classes\Themes;

use Illuminate\Filesystem\Filesystem;
use Module;

class laraPressViewFinder extends \Igaster\LaravelTheme\themeViewFinder{

    public $currentModule = null;

    public function __construct(Filesystem $files, array $paths, array $extensions = null){
        parent::__construct($files, $paths, $extensions);
    }

    // When a namespaced view is requested (eg 'namespace::view') then we will check if there is
    // a module registered under this namespace. If we find a module then we will redirect the
    // viewFinder to search under the module views path
    protected function findNamespacedView($name)
    {
        // get the namespace & view name
        list($namespace, $view) = $this->parseNamespaceSegments($name);
        
        // Check if there is a module registered under current $namespace
        if(Module::exists($namespace) && Module::isEnabled($namespace)){
            $this->currentModule = Module::where('slug', $namespace);
            // Add current theme path at the end of the search array
            $hints = array_merge($this->hints[$namespace], $this->paths);
            return $this->findInPaths($view, $hints);
        }
        
        // This is not a module, continue with default behavior. (Lookup in Theme/vendor/NAMESPACE)
        return parent::findNamespacedView($name);
    }

}
