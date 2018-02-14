<?php
if (!function_exists('module_url')) {

    function module_url($filename = null)
    {
    	$module_path = app()->make('view.finder')->currentModule['slug'];
        return theme_url("/modules/$module_path/$filename");
    }
}