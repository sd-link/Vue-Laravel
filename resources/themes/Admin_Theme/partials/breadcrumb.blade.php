    @php
        $breadcrumbs = explode("/",Route::getCurrentRoute()->uri());
        $second = 'core.'.$breadcrumbs[1];

        $second = AdminMenu::where('menu_id', $second)->first();
        $module = AdminMenu::getModule(Route::currentRouteName());
        $sub_menu = null;
        if($module)
            $sub_menu = $settings_menu = AdminMenu::getSubMenu($module);

         $third  = null;
         if (array_key_exists(2, $breadcrumbs)) {
             $third = 'core.'.$breadcrumbs[1].'.'.$breadcrumbs[2];
             $third = App\Models\Core\Settings\AdminMenu::where('menu_id', $third)->first();
        }
    @endphp

    <div id="backend-feedback-info" class="admin-notification callout callout-info" style="z-index:2222; display: none;">
        <p id="backend-feedback-text"></p>
    </div>

    <div class="content-header" style="display:flex; justify-content: space-between; @if(SiteSetting::isMobile()) display: none; @endif">
        <div class="content-header-left">
            <ol class="breadcrumb">
                <li><a href="{{ route('backend.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li>{{ $second->name }}</li>
                @if($third)
                    <li class="active">{{ $third->name }}</li>
                @endif
            </ol>
        </div>
        @if(SiteSetting::getAdminLayout() == 'topnav-wide' || SiteSetting::getAdminLayout() == 'topnav-boxed')
            <div class="content-header-right">
                    @if($sub_menu)
                        @foreach ($sub_menu as $key => $item)
                            @if($item->visible == 1)
                                @if(Route::currentRouteName() == $item->route)
                                    <span style="margin-left: 7px; padding: 7px 12px; width: 100px; background-color: rgba(255,255,255,0.04);"><a href="{{route($item->route)}}">{{ $item->name }}</a></span>
                                @else
                                    <span style="margin-left: 7px; padding: 7px 12px; width: 100px;"><a href="{{route($item->route)}}">{{ $item->name }}</a></span>
                                @endif
                            @endif
                        @endforeach
                    @endif
            </div>
        @endif
    </div>
