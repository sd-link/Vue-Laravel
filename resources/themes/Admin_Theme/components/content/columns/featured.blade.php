
    <div class="column featured_image_column" @if(!$show) style="display: none;" @endif>
        @if($single->featuredimage)
            <img class="featured_image img-responsive" src="{{ $single->featuredimage->grid_large }}" data-image-id="{{ $single->featuredimage->id }}">
        @else
            <img class="featured_image img-responsive" style="border: 1px dashed rgba(0,0,0, 0.09)" src="{{asset('images/transparent_grid_large.png')}}">
        @endif
    </div>
