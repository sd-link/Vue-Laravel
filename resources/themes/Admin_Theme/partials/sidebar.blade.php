@php
    $settings = SiteSetting::getSection('Admin.Colors');
@endphp


<sidebar inline-template :id=1
    init-sidebar-font-size="{{ $settings->main_menu_font_size }}"
    init-sidebar-font-color="{{ $settings->main_menu_font_color }}"
    init-sidebar-font-hover-color="{{ $settings->main_menu_font_hover_color }}"
    init-sidebar-main-background-color="{{ $settings->main_menu_main_background_color }}"
    init-sidebar-sub-background-color="{{ $settings->main_menu_sub_background_color }}"
    init-sidebar-menu-hover-color="{{ $settings->main_menu_menu_hover_color }}"

>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">
<!-- Sidebar user panel -->
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu">

        <li class="treeview" style="padding-top: 10px;">
            <a href="/backend/dashboard">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            </a>
        </li>

        @php
            $admin_menu = App\Models\Core\Settings\AdminMenu::where('parent', null)->orderBy('order')->get();
        @endphp

        @foreach ($admin_menu as $key => $item)
            @if($item->visible)
                @if($item->roles)
                    @php
                        $roles = "";
                        foreach ($item->roles as $key => $value) {
                            $roles .= $value .',';
                        }
                        $roles = rtrim($roles,',');
                        $myArray = explode(',', $roles);
                        // dd($myArray);
                    @endphp
                    @role($myArray)
                    <li class="treeview">
                        <a href="@if($item->route){{route($item->route)}}@else() # @endif">
                            <i class="{{ $item->icon }}"></i>  <span>{{ $item->name }}</span>
                            @if($item->children->count())
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            @endif
                        </a>
                        @if($item->children->count())
                            <ul class="treeview-menu menu-open" style="display: none;">
                                @foreach ($item->children as $key => $subitem)
                                    @if($subitem->visible)
                                        <li><i class="{{ $subitem->icon }}"></i> <a href="{{route($subitem->route)}}"> {{ $subitem->name }}</a></li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif
                    </li>
                    @endrole
                @else
                    <li class="treeview">
                        @if($item->children->count() == 0)
                            @if(strpos($item->menu_id, 'core.content') !== false)
                                <a href="{{ route($item->route, lcfirst($item->name)) }}">
                            @else
                                <a href="{{ route($item->route) }}">
                            @endif
                                <i class="{{ $item->icon }}"></i>  <span>{{ $item->name }}</span>
                                @if($item->children->count())
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                @endif
                            </a>
                        @else
                            <a href="@if($item->route){{ route($item->route) }}@else() # @endif">
                            <i class="{{ $item->icon }}"></i>  <span>{{ $item->name }}</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>

                            <ul class="treeview-menu menu-open" style="display: none;">
                                @foreach ($item->children as $key => $subitem)
                                    @if($subitem->visible)
                                        <li><a href="{{route($subitem->route)}}"> <i class="{{ $subitem->icon }}"></i> {{ $subitem->name }}</a></li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endif
            @endif
        @endforeach

        <!-- PAGES -->
        {{-- <li class="treeview">
            <a href="{{route('backend.pages.index')}}">
                <i class="fa fa-file-text"></i> <span>Pages</span>
            </a>
        </li> --}}

        <!-- BLOG -->
        {{-- <li class="treeview">
            <a href="#">
                <i class="fa fa-edit"></i> <span>Blog</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu menu-open" style="display: none;">
                <li><a href="{{route('backend.blog.index')}}"> Posts</a></li>
            @role(['admin', 'editor'])
                <li><a href="{{route('backend.blog.categories.index')}}"> Categories</a></li>
                <li><a href="{{route('backend.blog.tags.index')}}"> Tags</a></li>
            @endrole
            </ul>
        </li> --}}

        <!-- USER ROLE PERMISSION MANAGMENT -->
        {{-- @role(['admin', 'editor'])
        <li class="treeview">
            <a href="#">
                <i class="fa fa-users"></i> <span>Users</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu menu-open" style="display: none;">
            <li><a href="{{route('backend.users.index')}}"> Users</a></li>
            <li><a href="{{route('backend.roles.index')}}"> Roles</a></li>
            <li><a href="{{route('backend.permissions.index')}}"> Permissions</a></li>
            </ul>
        </li>
        @endrole --}}

        {{-- <li class="treeview">
          <a href="#">
            <i class="fa fa-wpforms" aria-hidden="true"></i> <span>Settings</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu menu-open" style="display: none;">
            <li><a href="{{route('backend.settings.admin.menu')}}"> Admin</a></li>
            <li><a href="{{route('backend.settings.website')}}"> Website</a></li>
            <li><a href="{{route('backend.settings.members')}}"> Members</a></li>
            <li><a href="{{route('backend.settings.comments')}}"> Comments</a></li>
            <li><a href="{{route('backend.forms.settings')}}"> Cache</a></li>
          </ul>
        </li> --}}
    </ul>
</section>
</aside>
</sidebar>
