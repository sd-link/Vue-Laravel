
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div id="disqus_thread"></div>
            </div>
        </div>
    </div>


    @push('scripts')
        <script>

        var disqus_config = function () {
            this.page.url = '{{route('frontend.blog.show', $post->slug)}}';  // Replace PAGE_URL with your page's canonical URL variable
            this.page.identifier = 'post/{{ $post->slug }}'; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
        };

        (function() { // DON'T EDIT BELOW THIS LINE
            var d = document, s = d.createElement('script');
            s.src = '//{{ Setting::get('Comments', 'disqus_channel') }}.disqus.com/embed.js';
            s.setAttribute('data-timestamp', +new Date());
            (d.head || d.body).appendChild(s);
        })();
        </script>
        <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
        <script id="dsq-count-scr" src="//{{ Setting::get('Comments', 'disqus_channel') }}.disqus.com/count.js" async></script>
    @endpush
