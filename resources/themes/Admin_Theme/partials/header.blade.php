  <header class="main-header">
    <!-- Logo -->
    @if( SiteSetting::getAdminLayout() == 'sidebar')
        <a href="{{config('app.url')}}" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>LP</b></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>Lara</b>****</span>
        </a>
    @endif
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-default navbar-static-top">
        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>

        <!-- Sidebar toggle button-->
        @if( SiteSetting::getAdminLayout() == 'sidebar')
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button" title="Toogle Sidbar">
                <span class="sr-only">Toggle navigation</span>
            </a>
        @endif
        @if(SiteSetting::getAdminLayout() == 'topnav-wide' || SiteSetting::getAdminLayout() == 'topnav-boxed')
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">
                        <b>Lara</b>****
                    </a>
                    <button class="navbar-toggle collapsed" data-target="#navbar-collapse" data-toggle="collapse" type="button">
                        @if( SiteSetting::getAdminLayout() == 'sidebar')
                            <i class="fa fa-bars"></i>
                        @else
                            Menu
                        @endif
                    </button>
                </div>
                @php
                    $admin_menu = AdminMenu::where('parent', null)->where('visible', 1)->orderBy('order')->get();
                @endphp
                <div class="collapse navbar-collapse nav-collapsed" id="navbar-collapse">
                    <ul class="nav navbar-nav">
                        @foreach ($admin_menu as $key => $item)
                            @if($item->visible)
                                @if($item->children->count() == 0)
                                    @if(strpos($item->module, 'Core.Content') !== false)
                                        <li><a href="{{ route($item->route, lcfirst($item->name)) }}">{{ $item->name }}</a></li>
                                    @else
                                        <li><a href="{{ route($item->route) }}">{{ $item->name }}</a></li>
                                    @endif
                                @else
                                    <li class="dropdown">
                                        <a aria-expanded="false" class="dropdown-toggle" data-toggle="dropdown" style="cursor: pointer;">{{ $item->name }}</a>
                                        <ul class="dropdown-menu" role="menu">
                                            @foreach ($item->children as $key => $subitem)
                                                @if($subitem->visible)
                                                    <li>
                                                        @if(strpos($subitem->menu_id, 'core.content.all') !== false)
                                                            <a href="{{ route($subitem->route, lcfirst($item->name)) }}">{{ $subitem->name }}</a>
                                                        @elseif(strpos($subitem->menu_id, 'core.content.taxonomy') !== false)
                                                            <a href="{{ route($subitem->route, [lcfirst($item->name), lcfirst($subitem->name)] ) }}">{{ $subitem->name }}</a>
                                                        @else
                                                            <a href="{{ route($subitem->route) }}">{{ $subitem->name }}</a>
                                                        @endif
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </li>
                                @endif
                            @endif
                        @endforeach
                        <li class="dropdown signout">
                            <a href="{{ url('/logout') }}"
                                onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                                Sign out
                            </a>
                        </li>
                    </ul>

                    <ul class="nav-user nav navbar-nav pull-right">
                      <!-- User Account: style can be found in dropdown.less -->
                      <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                          <img src="{{ Theme::url('images/avatar.png') }}" class="user-image" alt="User Image">
                          <span class="hidden-xs">@if(Auth::user()->firstname AND Auth::user()->lastname){{Auth::user()->firstname}} {{Auth::user()->lastname}}@else{{Auth::user()->username}}@endif</span>
                        </a>
                        <ul class="dropdown-menu">
                          <!-- Menu Footer-->
                          <li class="user-footer">
                              <a href="{{ url('/logout') }}"
                                  onclick="event.preventDefault();
                                           document.getElementById('logout-form').submit();">
                                  Sign out
                              </a>
                          </li>
                        </ul>
                      </li>
                    </ul>
                </div>
        @endif

    @if(SiteSetting::getAdminLayout() == 'topnav-wide' || SiteSetting::getAdminLayout() == 'topnav-boxed')
        </div>
    @endif
</nav>
</header>
