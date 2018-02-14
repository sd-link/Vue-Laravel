@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Dummy Module Dashboard</li>
  </ol>
</section>

<section class="content">

    <div class="row">
        <div class="col-md-12">
            <h1>Hello from Dummy Module</h1>
            <ul>
                <li>This is view [backend] defined at the module level.</li>
                <li>I extend [layouts.admin] view defined at the application level.</li>
                <li>I am located at [app/Modules/DummyModule/Resources/Views]</li>
                <li>Current theme is [{{Theme::get()}}]</li>
            </ul>
        </div>
    </div>
    
    <hr>
    <div class="row">
        <div class="col-md-6">
        <h1>Include a view from theme's path</h1>
            @component('components.box',[
                'title' => 'This is a Component', 
                'class' => 'box-success',
            ])
                This box is created from a component file located at the main app: larapress/resources/views/Admin_Theme/components/box.blade.php
            @endcomponent
        </div>        
    </div>

    <hr>
    <h1>Assets Test</h1>
    <p>1.) Lets try to get an image that is located on the Admin theme's path</p>
    <p>We use <strong>theme_url('happy.png')</strong> which will return: [{!!theme_url('images/avatar.png')!!}]</p>
    <img src="{{theme_url('images/avatar.png')}}" height="40" width="40">

    <p>2.) Now lets try to get an image that is located on the modules path: "public/modules/MODULE_SLUG"</p>
    <p>We use <strong>module_url('happy.png')</strong> which will return: [{!!module_url('happy.png')!!}]</p>
    <img src="{{module_url('happy.png')}}" height="40" width="40">
    <p>Can a theme override a module's asset? Yes it can. Put assets in [public/THEME_PATH/modules/MODULE_SLUG]</p>
</section>


@endsection
