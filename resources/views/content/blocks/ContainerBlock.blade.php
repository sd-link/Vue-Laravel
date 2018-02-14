@php
    $settings = $block->getSettings();

    $containerClass = "container";
    if($settings->containerType == 'fullwidth')
        $containerClass = "container-fullwidth";

    $deviceVisibility = array();
    $deviceVisibility['desktop'] = $settings->visibleDesktop;
    $deviceVisibility['tablet'] = $settings->visibleTablet;
    $deviceVisibility['mobile'] = $settings->visibleMobile;
    $shouldRender = ContentBlock::shouldRender(SiteSetting::getDeviceType(), $deviceVisibility);
@endphp
@if($shouldRender)
    <div class="{{$containerClass}} container-custom-{{ $block->id }}" >
            @component('content.render.renderblocks', [
                'deviceType' => $deviceType,
                'subBlocksIds' => $subBlocksIds,
                'allBlocks' => $allBlocks,
            ])@endcomponent
    </div>
@endif

@push('contentblockcss')
<style>
    .container-custom-{{ $block->id }} {
        min-height: {{ $settings->minHeight }};
        height: {{ $settings->height }};
        @if(SiteSetting::isDesktop())
            padding: {{$settings->padding}};
        @endif

        @if($settings->marginResponsive)
            @if(SiteSetting::isDesktop())
                margin: {{$settings->margin}};
            @elseif(SiteSetting::isTablet())
                margin: {{$settings->marginTablet}};
            @elseif(SiteSetting::isMobile())
                margin: {{$settings->marginMobile}};
            @endif
        @else
            margin: {{$settings->margin}};
        @endif

        @if($settings->display == 'flex')
            display: -webkit-box;
            display: -moz-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
            flex-direction: {{$settings->flexDirection}};
            justify-content: {{$settings->justifyContent}};
            align-items: {{$settings->alignItems}};
        @else
            display: block;
        @endif

        @if($settings->backgroundImage)
            background-image: url('{{$settings->backgroundImage}}');
            background-attachment: {{$settings->backgroundStyle}};
            background-position: {{$settings->backgroundPosition}};
            @if($settings->backgroundSize != 'manual')
                background-size: {{$settings->backgroundSize}};
            @else
                background-size: {{$settings->backgroundSizeManual}};
            @endif
            background-repeat: {{$settings->backgroundRepeat}};
            box-shadow: inset 0 0 0 2000px {{$settings->backgroundColor}};
        @else
            box-shadow: inset 0 0 0 2000px {{$settings->backgroundColor}};
        @endif
    }

    @if(SiteSetting::getDeviceType() == 'tablet')
        @if($settings->paddingResponsive)
            @media only screen and (orientation: landscape) {
                .container-custom-{{ $block->id }} {
                    padding: {{$settings->paddingTabletLandscape}};
                }
            }

            @media only screen and (orientation: portrait) {
                .container-custom-{{ $block->id }} {
                    padding: {{$settings->paddingTabletPortrait}};
                }
            }
        @else
            padding: {{$settings->padding}};
        @endif
    @endif

    @if(SiteSetting::getDeviceType() == 'mobile')
        @if($settings->paddingResponsive)
            @media only screen and (orientation: landscape) {
                .container-custom-{{ $block->id }} {
                    padding: {{$settings->paddingMobileLandscape}};
                }
            }

            @media only screen and (orientation: portrait) {
                .container-custom-{{ $block->id }} {
                    padding: {{$settings->paddingMobilePortrait}};
                }
            }
        @else
            padding: {{$settings->padding}};
        @endif
    @endif
</style>
@endpush
