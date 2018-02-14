@php
    $images = json_decode($block->content);
    // dd($images);
    $settings = $block->getSettings();
@endphp

<div class="images-block images-block-id-{{ $block->id }}">
    <div class="image-group">
        @foreach ($images as $key => $image)
            @if(isset($image->filename))
                <div class="image" style="background-color: {{ $settings->imageBackgroundColor }}; padding: {{ $settings->imagePadding }};">
                    {{-- TITLE --}}
                    @if($settings->imagesTitleEnabled)
                        <div class="{{$settings->imageTitleClass }}" style="order: {{ $settings->imageTitlePosition }}">
                            {{ $image->title }}
                        </div>
                    @endif

                    @php
                        if($settings->imageSize == 'original')
                            $imageUrl = '/' .  $image->path .  $image->filename . '.' .  $image->extension;
                        else
                            $imageUrl = '/' .  $image->path .  $image->filename .'_'. $settings->imageSize .'.' .  $image->extension;
                    @endphp
                    <a href="{{ '/' .  $image->path . $image->filename . '.' . $image->extension }}" class="image-link" style="order: 2;">
                        <img
                        class="{{ $settings->imageClass }}"
                        style="border: {{ $settings->imageOutline }} solid {{ $settings->imageOutlineColor }}; padding: {{ $settings->imageBorder }}; background-color: {{ $settings->imageBorderColor }}"
                        src="{{ $imageUrl }}"
                        title="{{ $image->title }}"
                        alt="{{ $image->title }}">
                    </a>

                    {{-- CAPTION --}}
                    @if($settings->imageCaptionEnabled)
                        <p style="order: 4;" class="{{$settings->imageCaptionClass}}">
                            {{ $image->caption }}
                        </p>
                    @endif
                </div>
            @endif
        @endforeach
    </div>
</div>
@push('contentblockcss')
    <style>
        .images-block-id-{{ $block->id }} {
            margin-left: -{{ $settings->spaceBetweenImages }};
            margin-right: -{{ $settings->spaceBetweenImages }};
        }

        .images-block-id-{{ $block->id }} .image {
            width: 100%;
            margin: {{ $settings->spaceBetweenImages }};
        }
        @php
            $space = preg_replace('/\D/', '', $settings->spaceBetweenImages);
            $offset = $space * 2;
        @endphp
        @media screen and (min-width: 600px) {
            .images-block-id-{{ $block->id }} .image {
                width: calc(50% - {{ $offset }}px);
                margin: {{ $settings->spaceBetweenImages }};
            }
        }
        @media screen and (min-width: 1000px) {
            .images-block-id-{{ $block->id }} .image {
                width: {{ $settings->imageWidth }};
                margin: {{ $settings->spaceBetweenImages }};
            }
        }
    </style>

@endpush
