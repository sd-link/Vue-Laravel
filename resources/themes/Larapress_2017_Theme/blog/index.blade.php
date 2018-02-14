@extends('layouts.app')

@section('content')
    <example></example>

    <div class="container" style="border: 0px solid green;">
        <div class="row">
            <div class="col-md-8" style="border: 0px solid red;">
                @if (!$posts->count())
                    <div class="alert alert-warning">
                      There is nothing here.
                    </div>
                @else
                    @if (isset($categoryName))
                        <div class="alert alert-info">
                          Category: <strong>{{$categoryName}}</strong>
                        </div>
                    @endif

                    @if (isset($authorName))
                        <div class="alert alert-info">
                          Author: <strong>{{$authorName}}</strong>
                        </div>
                    @endif

                    @foreach ($posts as $post)
                        <div class="panel panel-default" href="#" style="border-radius: 2px;">
                            @if($post->image)
                                <div class="panel-heading" style="padding: 0px;"><img class="img-responsive" src="/images/{{ $post->image }}" alt="{{ $post->title }}"></div>
                            @endif
                            <div class="panel-body" style="padding: 15px;">
                                <a href="{{route('frontend.blog.show', $post->slug)}}" class="blog-title"><h2>{{$post->title}}</h2></a>
                                <p>{{$post->excerpt}}</p>
                            </div>
                            <div class="panel-footer">
                                <span class="glyphicon glyphicon-user gi-small" aria-hidden="true"></span>
                                <a href="{{route('frontend.blog.author', $post->author->slug)}}"><span style="margin-right: 15px;"><small>{{$post->author->firstname}} {{$post->author->lastname}}</small></span></a>

                                <span class="glyphicon glyphicon-time gi-small" aria-hidden="true"></span>
                                <span style="margin-right: 15px;"><small>{{$post->date}}</small></span>

                                <span class="glyphicon glyphicon-folder-open gi-small" aria-hidden="true"></span>

                                    <span style="margin-right: 15px;">
                                        <small>
                                            @foreach ($post->categories as $category)
                                                <a href="{{route('frontend.blog.category', $category->slug)}}">
                                                    <span>{{$category->title}}</span>
                                                </a>@if(!$loop->last), @endif
                                            @endforeach
                                        </small>
                                    </span>


                                <span class="glyphicon glyphicon-comment gi-small" aria-hidden="true"></span>
                                <span style="margin-right: 15px;"><small>15 comments</small></span>

                                <a href="{{route('frontend.blog.show', $post->slug)}}"><span class="pull-right">read more &raquo;</span></a>
                            </div>
                        </div>
                    @endforeach
                @endif
                <nav>{{ $posts->links() }}</nav>
            </div>

            @include('includes.sidebar')

        </div>
    </div>
@endsection
