<?php

namespace App\Shortcodes;

class BoldShortcode {

    public function register($shortcode, $content, $compiler, $name)
    {
        // return sprintf('<strong class="%s">%s</strong>', $shortcode->class, $content);
        // return view('shortcode.form', compact('content', 'shortcode'));
    }

}
