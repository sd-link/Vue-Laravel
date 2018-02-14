<content-data inline-template
    :init-is-desktop={{ SiteSetting::isDesktop() ? 'true':'false'}}>

    <div id="content_display" ref="contentList" class="{{ $class }}">
        {{-- Header --}}
        @if(isset($header))
            {{ $header }}
        @endif

        @if(isset($content))
            {{ $content }}
        @endif

        @if(isset($header))
            {{ $header }}
        @endif
    </div>
</content-data>
