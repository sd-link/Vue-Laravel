<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Lara**** | Admin</title>
    {{-- <link href="{{ asset('css/markdown-plus.min.css') }}" rel="stylesheet"> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
    <!-- Styles -->
    {{-- <link href="/css/app.css" rel="stylesheet"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">

    {{-- animate.css --}}
    <link href="https://cdn.jsdelivr.net/npm/animate.css@3.5.1" rel="stylesheet" type="text/css">

    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.css">


    <!-- Theme style -->
    {{-- {!! Theme::css('css/AdminLTE.css') !!} --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.3.11/css/AdminLTE.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />
    {!! Theme::css('css/skins/skin-blue.min.css') !!}
    {!! Theme::css('css/skins/iCheck/square/blue.css') !!}
    {!! Theme::css('css/sweetalert2.css') !!}

    {{-- <link rel="stylesheet" href="{{asset('themes/Admin_Theme/css/style.css')}}?{{ str_random(7) }}"> --}}

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.3.6/css/bootstrap-colorpicker.min.css">

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,400i,600,700,800" rel="stylesheet">

    <!-- {!! Theme::css('css/style.css') !!} -->
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.scrollbar/0.2.11/jquery.scrollbar.min.css"> --}}
    <link rel="stylesheet" href="{{asset('themes/Admin_Theme/css/scrollbar.css')}}?{{ str_random(7) }}">
    {{-- <link rel="stylesheet" href="{{asset('css/dropzone.css')}}"> --}}
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    {{-- <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet"> --}}

    <link rel="stylesheet" href="{{asset('themes/Admin_Theme/css/style.css')}}?{{ str_random(7) }}">
    <link rel="stylesheet" href="{{asset('themes/Admin_Theme/css/custom-css.css')}}?{{ str_random(7) }}">

    <style media="screen">
        .chosen {
            color: #fff;
            background-color: #c00;
        }
    </style>

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
    {{-- <link href="{{ asset('images/icon.png') }}" rel="shortcut icon" type="image/png"/> --}}
    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
    {{-- <script src="//cdnjs.cloudflare.com/ajax/libs/Sortable/1.4.2/Sortable.min.js"></script> --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script> --}}

    {!! Theme::js('js/sweetalert2.js') !!}
    <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/locale/sv.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.11/moment-timezone-with-data.min.js"></script>
    {{-- <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script> --}}
    <script src="{{ asset('js/jquery.localtime.js') }}"></script>
    <script type="text/javascript">$.localtime.setFormat("yyyy-mm-dd HH:mm");</script>

    {!! Theme::js('js/iCheck/icheck.min.js') !!}

    <script>
      $(function () {
        $('.icheck_input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '10%' // optional
        });
      });
    </script>
</head>
<body class="hold-transition skin-blue {{ SiteSetting::getAdminLayoutCssClass() }}">

        <div id="app" class="wrapper">
            @if( SiteSetting::getAdminLayout() == 'sidebar')
                @include('partials.header')
                @include('partials.sidebar')
                <div class="content-wrapper">
                    @yield('content')
                </div>
                <footer class="main-footer">
                    @include('partials.footer')
                </footer>
            @else
                @include('partials.header')
                <div class="content-wrapper">
                    <div class="container container-bkg" style="min-height: 957px;">
                        @yield('content')
                    </div>
                </div>
                <footer class="main-footer">
                    <div class="container">
                        @include('partials.footer')
                    </div>
                </footer>
            @endif
        </div>


    <!-- page script -->
    <script type="text/javascript">
        // Set active state on menu element
        var current_url = "{{ url(Route::current()->uri()) }}";

        $("ul.sidebar-menu li a").each(function() {
            if ($(this).attr('href').startsWith(current_url) || current_url.startsWith($(this).attr('href') ))
            {
                $(this).parents('li').addClass('active')
                // $(this).parents('.treeview').children('a').css('background', 'blue')
                $(this).parents('ul').attr('style', 'display: block')
            }
        });
    </script>

    {!! Theme::js('js/app.min.js') !!}
    {{-- <script src="{{ asset('js/adminvue.js') }}?{{ str_random(7) }}"></script> --}}

    @routes
    {{-- @yield('scripts') --}}
    @stack('scripts')
</body>
</html>
