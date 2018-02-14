@extends('layouts.app')

@section('content')
    <div class="container" style="border: 0px solid green;">
        <div class="row">

            <div class="col-md-8" style="border: 0px solid red;">
                <div class="panel panel-default" href="#" style="border-radius: 2px;">
                    @if($post->image)
                        <div class="panel-heading" style="padding: 0px;"><img class="img-responsive" src="/images/{{$post->image}}" alt="{{ $post->title }}"></div>
                    @endif

                    <div class="panel-body" style="padding: 15px;">
                        <h2>{{$post->title}}</h2>
                        {{-- <p>{!! ($body) !!}</p> --}}
                        <p id="postBody"></p>
                        {!! $post->body !!}
                    </div>
                    @php
                        $author = $post->author;
                    @endphp
                    <div class="panel-footer">
                        <span class="glyphicon glyphicon-user gi-small" aria-hidden="true"></span>
                        <a href="{{route('frontend.blog.author', $author->slug)}}"><span style="margin-right: 15px;"><small>{{$author->firstname}} {{$author->lastname}}</small></span></a>

                        <span class="glyphicon glyphicon-time gi-small" aria-hidden="true"></span>
                        <span style="margin-right: 15px;"><small>{{$post->date}}</small></span>

                        <span class="glyphicon glyphicon-folder-open gi-small" aria-hidden="true"></span>
                        {{-- <a href="{{route('frontend.blog.category', $post->category->slug)}}"><span style="margin-right: 15px;"><small>{{$post->category->title}}</small></span></a> --}}

                        <span class="glyphicon glyphicon-comment gi-small" aria-hidden="true"></span>
                        <span style="margin-right: 15px;"><small>15 comments</small></span>
                    </div>

                    <div class="panel-footer">
                        @include('social.share', ['url' => 'http://blog.damirmiladinov.com/'])
                    </div>

                </div>

                <div class="panel panel-default" href="#" style="border-radius: 2px;">
                    <div class="panel-body">
                        <div style="float: left; border: 0px solid red; margin-right: 15px; width: 101px;">
                            <img style="border-radius: 50%;" src="{{ $author->gravatar() }}" alt="">
                        </div>
                        <div style="border: 0px red solid; margin-left: 116px;">
                            <div style="border: 0px solid red; margin-bottom: 5px;"><b>{{$author->firstname}} {{$author->lastname}}</b></div>
                            <div style="border: 0px solid red; margin-bottom: 5px;"><span class="glyphicon glyphicon-book gi-small" aria-hidden="true"></span> {{ $author->posts()->published()->count() }} posts</div>
                            <div style="border: 0px solid red;">
                                {{$author->bio}}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- comments here --}}
                @if(Setting::get('Comments', 'type') == 'native')
                    @include('blog.comments')
                @elseif(Setting::get('Comments', 'type') == 'disqus')
                    @include('blog.disqus')
                @endif
            </div>

            @include('includes.sidebar')

        </div>
    </div>
@endsection


@push('scripts')
    <script>

        var popupSize = {
            width: 780,
            height: 550
        };

        $(document).on('click', '.social-buttons > a', function(e){

            var
                verticalPos = Math.floor(($(window).width() - popupSize.width) / 2),
                horisontalPos = Math.floor(($(window).height() - popupSize.height) / 2);

            var popup = window.open($(this).prop('href'), 'social',
                'width='+popupSize.width+',height='+popupSize.height+
                ',left='+verticalPos+',top='+horisontalPos+
                ',location=0,menubar=0,toolbar=0,status=0,scrollbars=1,resizable=1');

            if (popup) {
                popup.focus();
                e.preventDefault();
            }

        });
    </script>
@endpush
