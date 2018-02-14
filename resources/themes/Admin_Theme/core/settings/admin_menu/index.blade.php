@extends('layouts.admin')

@section('content')
    <!-- Content Wrapper. Contains page content -->

        <div id="backend-feedback-info" class="admin-notification callout callout-info" style="display: none;">
            <p id="backend-feedback-text"></p>
        </div>

        @php
            $admin_menu = AdminMenu::where('parent', null)->orderBy('order')->get();
        @endphp

        <!-- Content Header (Page header) -->
        {{-- @include('partials/breadcrumb') --}}

        <!-- Main content -->
        <section class="content {{ SiteSetting::getDeviceType() }}">
            <div class="row">
                <div class="col-md-{{ SiteSetting::getColumnWidth() }}">
                    <div class="box box-primary" style="min-height: 525px;">
                        <div class="box-header with-border">
                            <h3 class="box-title">Admin Settings</h3>
                        </div>
                        <div class="box-body noselect">
                            <ul class="nav nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#menu">Menu</a></li>
                                    <li><a data-toggle="tab" href="#theme">Theme & Layout</a></li>
                                    <li><a data-toggle="tab" href="#login">Login</a></li>
                                </ul>

                                <div class="tab-content">
                                    <div id="menu" class="tab-pane fade in active" style="min-height: 420px;">
                                        <small>Visibility changes made to the menu will not apply to superadmin, only to admin and below admin roles. Changes made here are automatically saved.</small>
                                        <div class="form-group" id="admin_menu">
                                            @foreach ($admin_menu as $key => $item)
                                                <admin-main-menu-item
                                                    :id={{ $item->id }}
                                                    :order={{ $key }}
                                                    init-name="{{ $item->name }}"
                                                    init-icon="{{ $item->icon }}"
                                                    :init-visible={{ $item->visible }} @if($item->children->count()) :has-children=true @endif>
                                                    @if($item->children->count())
                                                        @foreach ($item->children as $key => $subitem)
                                                            <admin-sub-menu-item
                                                                :subitem=true :id={{ $subitem->id }}
                                                                init-name="{{ $subitem->name }}"
                                                                init-icon="{{ $subitem->icon }}"
                                                                :visible={{ $subitem->visible }}>
                                                            </admin-sub-menu-item>
                                                        @endforeach
                                                    @endif
                                                </admin-main-menu-item>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div id="theme" class="tab-pane fade" style="min-height: 420px;">
                                        @include('core.settings.admin_menu.theme')
                                    </div>

                                    {{-- <div id="fontcolors" class="tab-pane fade" style="min-height: 420px;">
                                        @include('core.settings.admin_menu.colors')
                                    </div>--}}
                                    <div id="login" class="tab-pane fade">
                                        @include('core.settings.admin_menu.login')
                                    </div>
                                </div>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.5.1/Sortable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.3.6/js/bootstrap-colorpicker.min.js"></script>
    <script src="{{ asset('js/adminvue.js') }}?{{ str_random(7) }}"></script>
    <script>

        var layout = '{{ SiteSetting::get('Admin.Layout','layout') }}'
        var theme = '{{ SiteSetting::get('Admin.Layout','theme') }}'

        var $layout_radio = $('input:radio[name=layout]');
        $layout_radio.filter('[value='+layout+']').prop('checked', true);

        var $theme_radio = $('input:radio[name=theme]');
        $theme_radio.filter('[value='+theme+']').prop('checked', true);

        var blog_layout = '{{ SiteSetting::get('Admin.Content','blog_index_view') }}'
        var pages_layout = '{{ SiteSetting::get('Admin.Content','pages_index_view') }}'

        var $blog_view = $('input:radio[name=blog_index_view]');
        $blog_view.filter('[value='+blog_layout+']').prop('checked', true);

        var $pages_view = $('input:radio[name=pages_index_view]');
        $pages_view.filter('[value='+pages_layout+']').prop('checked', true);

        var login_horisontal_position = '{{ SiteSetting::get('Admin.login','login_horisontal_position') }}'
        $('#login_horisontal_position').val(login_horisontal_position)

        $('#login_background_color').colorpicker()
        $('#login_logo_text_color').colorpicker()

        $('#saveThemeSettingsBtn').on('click', function(event) {
            $.ajax('{{route('backend.settings.admin.theme.save')}}', {
                method: 'POST',
                dataType:'json',
                data: {
                    layout: $("input:radio[name ='layout']:checked").val(),
                    theme: $("input:radio[name ='theme']:checked").val(),
                    _token: Laravel.csrfToken
                },
                success: function(result) {
                    // location.reload();
                },
                error: function(result) {

                }
            });
        });

        $('#saveLoginSettingsBtn').on('click', function(event) {
            $.ajax('{{route('backend.settings.admin.login.save')}}', {
                method: 'POST',
                dataType:'json',
                data: {
                    login_background_color: $('#login_background_color').colorpicker('getValue'),
                    login_logo_text_color: $('#login_logo_text_color').colorpicker('getValue'),
                    login_background_image: $('#login_background_image').val(),
                    login_logo_text: $('#login_logo_text').val(),
                    login_logo_image: $('#login_logo_image').val(),
                    login_horisontal_position: $('#login_horisontal_position').val(),
                    _token: Laravel.csrfToken
                },
                success: function(result) {
                    console.log('saved')
                },
                error: function(result) {

                }
            });
        });

        Sortable.create(admin_menu, {
            sort: true,
            chosenClass: 'admin-menu-sortable-chosen',
            handle: ".admin-menu-sortable-handle",
            animation: 150,
            onUpdate: function (event) {
                var order = saveOrder()
                console.log('order:' + order)
                $.ajax({
                    data: {
                        'ids[]': order,
                        gallery_id: $('#gallery_id').val(),
                        _token: Laravel.csrfToken
                    },
                    type: 'POST',
                    url: '{{ route('backend.settings.admin.menu.item.order') }}'
                })
            }
        });

        function saveOrder() {
            var order = new Array()

            $('#admin_menu').children().each(function() {
                order.push($(this).attr("id"))
            })

            return order
        }

        // Style Input Number Spinners
        jQuery('<div class="spinner-nav"><div class="spinner-button spinner-up">+</div><div class="spinner-button spinner-down">-</div></div>').insertAfter('.spinner input');
        jQuery('.spinner').each(function() {
          var spinner = jQuery(this),
            input = spinner.find('input[type="number"]'),
            btnUp = spinner.find('.spinner-up'),
            btnDown = spinner.find('.spinner-down'),
            min = input.attr('min'),
            max = input.attr('max');

          btnUp.click(function() {
            var oldValue = parseFloat(input.val());
            if (oldValue >= max) {
              var newVal = oldValue;
            } else {
              var newVal = oldValue + 1;
            }
            spinner.find("input").val(newVal);
            spinner.find("input").trigger("change");
            spinner.find("input").trigger("input");
          });

          btnDown.click(function() {
            var oldValue = parseFloat(input.val());
            if (oldValue <= min) {
              var newVal = oldValue;
            } else {
              var newVal = oldValue - 1;
            }
            spinner.find("input").val(newVal);
            spinner.find("input").trigger("change");
            spinner.find("input").trigger("input");
          });
        });
    </script>
@endpush
