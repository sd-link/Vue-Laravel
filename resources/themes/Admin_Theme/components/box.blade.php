{{--
    Parameters:
		- $title
		- $class : 'box-primary', box-success', 'box-warning', 'box-danger',  null

    Slots:
		- footer
		- headerButtons

	Example:

		@component('components.box',[
			'title' => 'One box', 
			'class' => 'box-success',
		])

			Main Content

			@slot('headerButtons')
				<span class="label label-warning">Notification</span>
				<div class="btn-group">
					<button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown"><i class="fa fa-wrench"></i></button>
					<ul class="dropdown-menu" role="menu">
						<li><a href="#">Action</a></li>
						<li><a href="#">Action</a></li>
						<li class="divider"></li>
						<li><a href="#">Action</a></li>
					</ul>
				</div>
			@endslot

			@slot('footer')
				<div class="row">
					<div class="col-md-12">
						Footer goes here
					</div>
				</div>
			@endslot

		@endcomponent
 --}}

<div class="box {{ $class or ''}}">
	<div class="box-header with-border">
		<h3 class="box-title">
			{{ $title or 'Title'}}
		</h3>
		<div class="box-tools pull-right">
			{{ $headerButtons or '' }}
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
		</div>
	</div>
	<div class="box-body">

		{{ $slot }}

	</div> <!-- ./box-body -->

	@if(isset($footer))
		<div class="box-footer">
			{{ $footer }}
		</div> <!-- /.box-footer -->
	@endif
</div>	<!-- /.box -->