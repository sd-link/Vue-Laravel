<?php

namespace App\Shortcodes;
use App\Models\Core\Forms\Form;


class FormShortcode {

    public function register($shortcode, $content, $compiler, $name)
    {
        $form = Form::where('id', $shortcode->id)->first();
        return view('shortcode.form', compact('form'));
    }

}
