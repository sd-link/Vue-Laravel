
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Featured Image</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div class="box-body">
        <div id="featured_image_feedback_info" class="callout callout-warning" style="display: none; margin-bottom: 12px;">
            <p id="featured_image_feedback_text"></p>
        </div>

        @if(isset($content))
            <div class="form-group text-center set_featured_image @if($content->featuredimage) featured-image-set @else() featured-image-not-set @endif" data-toggle="modal" data-target="#mediaModal" data-backdrop="static" data-keyboard="false" data-id="{{ $content->id }}">
                <div class="set_featured_image_text" style="@if($content->featuredimage) display: none; @endif">Click to set featured image.</div>
                <img data-image-id="@if($content->featuredimage) {{ $content->featuredimage->id }} @endif"
                    class="img-responsive featured_image"
                    src="@if($content->featuredimage){{$content->featuredimage->medium}}@endif">
            </div>
            <button id="removeFeaturedImageBtn" data-id="{{ $content->id }}" style="@if(!$content->featuredimage) display: none; @endif" type="button" class="btn btn-primary btn-block"> Remove Featured Image</button>
        @else
            <div class="form-group text-center set_featured_image featured-image-not-set" data-toggle="modal" data-target="#mediaModal" data-backdrop="static" data-keyboard="false" data-id="">
                <div class="set_featured_image_text">Click to set featured image.</div>
                <img data-image-id=""
                    class="img-responsive featured_image"
                    src="">
            </div>
            <button id="removeFeaturedImageBtn" data-id="" style="display: none;" type="button" class="btn btn-primary btn-block"> Remove Featured Image</button>
        @endif

        <modal-test>

        </modal-test>
    </div>
</div>
