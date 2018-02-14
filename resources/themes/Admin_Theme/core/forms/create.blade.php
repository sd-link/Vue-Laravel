@extends('layouts.admin')

@section('content')
        <div id="backend-feedback-info" class="admin-notification callout callout-info" style="display: none;">
            <p id="backend-feedback-text"></p>
        </div>

        @include('core.forms.emailsconfig')
        <!-- Main content -->
        <section class="content {{ SiteSetting::getDeviceType() }}">
            <div class="row">
                <div class="col-md-3">
                    <div class="box box-primary" style="min-height: 525px;">
                        <div class="box-header">
                            <h3 class="box-title">Form Fields</h3>
                        </div>
                        <div class="box-body">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="form-fields-tab-nav active"><a data-toggle="tab" href="#tabFormFields" aria-expanded="true">Add Fields</a></li>
                                    <li class="field-options-tab-nav"><a data-toggle="tab" href="#tabFieldOptions"  aria-expanded="false">Field Options</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div id="tabFormFields" class="tab-pane active">
                                        <div class="form-group">
                                            <label for="slug">Common Fields</label>
                                            <div id="user_fields" class="form-group">
                                                @include('core.forms.fields.common')
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="slug">Basic Fields</label>
                                            <div id="basic_fields" class="form-group">
                                                @include('core.forms.fields.basic')
                                            </div>
                                        </div>

                                    </div>
                                    <!-- /.tab-pane -->
                                    <div id="tabFieldOptions" class="tab-pane">
                                        @include('core.forms.fields.options.field')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="box box-primary" style="min-height: 525px;">
                        <div class="box-header">
                            <h3 class="box-title">Form Design</h3>
                        </div>

                        <div class="box-body">
                            <div id="field-feedback-info" style="display: none;" class="form-field-only-once">
                                <span id="field-feedback-text"></span>
                            </div>

                            <div id="form_editor" class="list-group" style="border: 0px solid black; min-height: 400px">
                                <div id="emptyFormText" class="text-center" style="padding-top: 150px; color: #00c0ef;">
                                    <h4>Drag and drop fields here to design your form.</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Form Options</h3>
                        </div>

                        {{-- @include('admin.tags.message') --}}
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="form-basic-tab-nav active"><a data-toggle="tab" href="#tabBasic" aria-expanded="true">Basic</a></li>
                                    <li class="field-actions-tab-nav"><a data-toggle="tab" href="#tabActions"  aria-expanded="false">Actions</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div id="tabBasic" class="tab-pane active">
                                        <div class="form-group title-group">
                                            <label for="title">Form Title</label>
                                            <input type="text" id="title" value="" placeholder="Enter form title" class="form-control">

                                            <span class="help-block">
                                                <small class="title-feedback"></small>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <div class="checkbox icheck">
                                                <label>
                                                   <input id="captcha" type="checkbox"> <span style="margin-left: 10px;">Enable Captcha</span>
                                               </label>
                                           </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="checkbox icheck">
                                                <label>
                                                   <input id="success_message" type="checkbox" checked> <span style="margin-left: 10px;">Success Message</span>
                                               </label>
                                               <textarea id="success_message_text" style="margin-top: 10px;" name="name" class="form-control" placeholder="Enter success message">Your message has been successfully submited. We'll get back to you soon.</textarea>
                                           </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="checkbox icheck">
                                                <label>
                                                   <input id="redirect" type="checkbox"> <span style="margin-left: 10px;">Redirect</span>
                                               </label>
                                           </div>
                                           <div style="margin-top: 10px;">
                                                 <input id="redirect_url" type="text" checked placeholder="Enter redirect url" class="form-control">
                                           </div>
                                        </div>
                                        <div class="">
                                            Codes you can use for email template: <br>
                                            <span id="codes"></span>
                                        </div>
                                    </div>
                                    <div id="tabActions" class="tab-pane">

                                        <table class="table">
                                            <tr>
                                                <td style="width: 95%;">
                                                    <div class="checkbox icheck form-group">
                                                        <label style="padding-right: 50px;">
                                                           <input id="email_admin" type="checkbox" checked> <span style="margin-left: 10px;">Email Admin</span>
                                                       </label>
                                                       <input id="email_admin_subject" type="hidden">
                                                       <textarea id="email_admin_message" style="display: none;">123</textarea>
                                                   </div>
                                                </td>
                                                <td><i style="cursor: pointer;" class="fa fa-cog" aria-hidden="true"
                                                    data-email-config="admin"
                                                    data-toggle="modal" data-target="#emailsConfigModal"
                                                    data-backdrop="static" data-keyboard="false"></i></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="checkbox icheck form-group">
                                                        <label style="padding-right: 50px;">
                                                           <input id="email_submitter" type="checkbox"> <span style="margin-left: 10px;">Email Submitter</span>
                                                       </label>
                                                       <input id="email_submitter_subject" type="hidden">
                                                       <textarea id="email_submitter_message" style="display: none;">abc</textarea>
                                                   </div>
                                                </td>
                                                <td><i style="cursor: pointer;" class="fa fa-cog" aria-hidden="true"
                                                    data-email-config="submitter"
                                                    data-toggle="modal" data-target="#emailsConfigModal"
                                                    data-backdrop="static" data-keyboard="false"></i></td>
                                            </tr>
                                        </table>

                                    </div>
                                </div>
                        </div>
                        <div class="box-footer">
                            <button id="saveBtn" style="width: 70px;" class="btn btn-primary pull-right" type="submit">Save</button>
                        </div>

                        <!-- /.box-body -->
                    </div>
                </div>

            </div>
        </section>
@endsection

@push('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.5.1/Sortable.min.js"></script>
    <script src="{{asset('js/formseditor.js')}}?{{ str_random(7) }}"></script>
    <script src="{{asset('js/publishable.js')}}?{{ str_random(7) }}"></script>

    <script>

        var form_update_url = '{{route('backend.forms.update')}}'
        var redirectTo = '{{route('backend.forms.edit', 0)}}'

        @include('core.forms.templates.all')

        var options = new Object()
        var actions = new Object()
        var emails = new Object()
        var admin = new Object()
        var submitter = new Object()

        admin.subject = 'You got email!'
        admin.message = @include('core.forms.templates.emails.admin')
        submitter.subject = 'Thank you for contacting us!'
        submitter.message = @include('core.forms.templates.emails.submitter')

        emails.admin = admin
        emails.submitter = submitter
        options.emails = emails

        console.dir(options)

        $("#saveBtn").on("click", function(e) {
            if(canWeSaveTheForm()) {
                var saveBtn = $(this)
                disableBtn(saveBtn, 'Saving')
                var fields = []
                var formOptions = []

                $('#form_editor').find('.selectable').each(function(i, obj) {
                    fields.push($(obj).data('options'))
                });

                options.captcha = $('#captcha').is(":checked")
                options.success_message = $('#success_message').is(":checked")
                options.success_message_text = $('#success_message_text').val()
                options.redirect = $('#redirect').is(":checked")
                options.redirect_url = $('#redirect_url').val()

                actions.admin = $('#email_admin').is(":checked")
                actions.submitter = $('#email_submitter').is(":checked")
                options.actions = actions

                formOptions = {
                    fields: fields,
                    options: options
                }
                // console.log('options: '+ JSON.stringify(formOptions))
                $.ajax('{{route('backend.forms.store')}}', {
                    method: 'POST',
                    dataType:'json',
                    data: {
                        meta: formOptions,
                        title: $('#title').val(),
                        _token: Laravel.csrfToken
                    },
                    success: function(result) {
                        redirectTo = redirectTo.replace('0', result.form.id);
                        window.location.href = redirectTo;
                    },
                    error: function(result) {
                        enableBtn(saveBtn, 'Save');
                        var errors = result.responseJSON;
                        if(errors) {
                            $('.content').find('.title-group').removeClass('has-error');
                            $('.content').find('.title-feedback').html('');

                            if(typeof errors.title !== 'undefined'){
                                $('.content').find('.title-group').addClass('has-error');
                                $('.content').find('.title-feedback').html(errors.title);
                            }
                        } else {
                            feedback("There was some undefined error while trying to save.", "callout-danger", 5000);
                        }                    }
                });
            } else {
                feedback("Can't save, each field id must be unique.", "callout-danger", 4000);
            }
        })
    </script>
@endpush
