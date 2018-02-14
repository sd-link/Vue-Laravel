@extends('layouts.app')

@section('content')
    <div class="container" style="border: 0px solid green;">
        <div class="row">
        	<div class="col-md-12">

	            <h1>Hello from Dummy Module</h1>
	            <ul>
	                <li>This is view [frontend] defined at the module level.</li>
	                <li>I extend [layouts.app] view defined at the application level.</li>
	                <li>I am located at [app/Modules/DummyModule/Resources/Views]</li>
	                <li>Current theme is [{{Theme::get()}}]</li>
	            </ul>

        	</div>
        </div>
    </div>
@endsection
