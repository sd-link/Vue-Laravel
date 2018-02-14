@php
    $content = json_decode($block->content);
    $settings = $block->getSettings();

    if(isset($content->filename)) {
        $imageUrl = '/' .  $content->path .  $content->filename . '_grid_medium.' .  $content->extension;

        switch ($settings->imageSize) {
            case 'original':
                $imageUrl = '/' . $content->path .  $content->filename . '.' .  $content->extension;
            break;

            case 'large':
                $imageUrl = '/' .  $content->path .  $content->filename . '_large.' .  $content->extension;
            break;

            case 'medium':
                $imageUrl = '/' .  $content->path .  $content->filename . '_medium.' .  $content->extension;
            break;

            case 'grid_large':
                $imageUrl = '/' .  $content->path .  $content->filename . '_grid_large.' .  $content->extension;
            break;

            case 'grid_medium':
                $imageUrl = '/' .  $content->path .  $content->filename . '_grid_medium.' .  $content->extension;
            break;

            case 'thumb_large':
                $imageUrl = '/' .  $content->path .  $content->filename . '_thumb_large.' .  $content->extension;
            break;

            case 'thumb':
                $imageUrl = '/' .  $content->path .  $content->filename . '_thumb.' .  $content->extension;
            break;

            default:
                $imageUrl = '/' .  $content->path .  $content->filename . '_grid_medium.' .  $content->extension;
        }
    }
@endphp

@if(isset($content->filename))
    <div class="image-block">
        @if($settings->imageTitleEnabled)
            <div class="{{$settings->imageTitleClass}}" style="order: {{ $settings->imageTitlePosition }}">{{ $content->title }}</div>
        @endif

        <a style="order: 2;" href="{{ '/' .  $content->path . $content->filename . '.' . $content->extension }}" class="image-link">
            <img class="{{ $settings->imageClass }}" src="{{ $imageUrl }}" title="{{ $content->title }}">
        </a>

        @if($settings->imageCaptionEnabled)
            <p style="order: 4;" class="{{$settings->imageCaptionClass}}">
                {{ $content->caption }}
            </p>
        @endif
    </div>
@endif
