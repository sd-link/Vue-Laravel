
            <div class="col-md-4" style="border: 0px solid red;">
                {{-- CATEGORIES --}}
                <div class="panel panel-default" href="#" style="border-radius: 2px;">
                    <div class="panel-heading" style="background-color: #fcfcfc;">Categories</div>
                    <div class="panel-body" style="padding: 15px;">
                        @foreach ($categories as $category)
                            <div style="padding: 10px; padding-left: 5px; border-bottom: 1px solid #d3e0e9;">
                                <a href="{{route('frontend.blog.category', $category->slug)}}">{{$category->title}}</a>
                                <div class="pull-right" style="background-color: #d3e0e9; padding: 0px 10px; border-radius: 15%;">{{$category->posts->count()}}</div>
                            </div>
                        @endforeach

                    </div>
                </div>

                {{-- POPULAR POSTS --}}
                <div class="panel panel-default" href="#" style="border-radius: 2px;">
                    <div class="panel-heading" style="background-color: #fcfcfc;">Popular Posts</div>
                    <div class="panel-body" style="padding: 15px;">
                        @foreach ($popularPosts as $popularPost)
                            <div style="padding: 10px; padding-left: 5px;">
                                <a class="blog-title" href="{{route('frontend.blog.show', $popularPost->slug)}}">{{$popularPost->title}}</a>
                                <div style="color: #aaa;"><small>{{ $popularPost->date }}</small></div>
                            </div>
                        @endforeach

                    </div>
                </div>


                {{-- TAGS --}}
                <div class="panel panel-default" href="#" style="border-radius: 2px;">
                    <div class="panel-heading" style="background-color: #fcfcfc;">Tags</div>
                    <div class="panel-body" style="padding: 15px;">
                        <div style="padding: 1px 8px; border: 1px solid #d3e0e9; margin-right: 5px; margin-bottom: 5px; float: left;">php</div>
                        <div style="padding: 1px 8px; border: 1px solid #d3e0e9; margin-right: 5px; margin-bottom: 5px; float: left;">code igniter</div>
                        <div style="padding: 1px 8px; border: 1px solid #d3e0e9; margin-right: 5px; margin-bottom: 5px; float: left;">laravel</div>
                        <div style="padding: 1px 8px; border: 1px solid #d3e0e9; margin-right: 5px; margin-bottom: 5px; float: left;">yii</div>
                        <div style="padding: 1px 8px; border: 1px solid #d3e0e9; margin-right: 5px; margin-bottom: 5px; float: left;">ruby on rails</div>
                        <div style="padding: 1px 8px; border: 1px solid #d3e0e9; margin-right: 5px; margin-bottom: 5px; float: left;">jQuery</div>
                        <div style="padding: 1px 8px; border: 1px solid #d3e0e9; margin-right: 5px; margin-bottom: 5px; float: left;">vue js</div>
                        <div style="padding: 1px 8px; border: 1px solid #d3e0e9; margin-right: 5px; margin-bottom: 5px; float: left;">react js</div>
                    </div>

                </div>

            </div>
