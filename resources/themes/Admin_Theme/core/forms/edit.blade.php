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
                                        <div class="form-group">
                                            <label for="title">Form Title</label>
                                            <input type="text" id="title" value="{{ $form->title }}" placeholder="Enter form title" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <div class="checkbox icheck">
                                                <label>
                                                   <input id="captcha" type="checkbox" @if($form->captcha) checked @endif> <span style="margin-left: 10px;">Enable Captcha</span>
                                               </label>
                                           </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="checkbox icheck">
                                                <label>
                                                   <input id="success_message" type="checkbox" @if($form->success_message) checked @endif> <span style="margin-left: 10px;">Success Message</span>
                                               </label>
                                               <textarea id="success_message_text" style="margin-top: 10px;" name="name" class="form-control" placeholder="Enter success message">{{ $form->success_message_text}}</textarea>
                                           </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="checkbox icheck">
                                                <label>
                                                   <input id="redirect" type="checkbox" @if($form->redirect) checked @endif> <span style="margin-left: 10px;">Redirect</span>
                                               </label>
                                           </div>
                                           <div style="margin-top: 10px;">
                                                 <input id="redirect_url" type="text" @if($form->redirect_url)value="{{$form->redirect_url}}"@endif placeholder="Enter redirect url" class="form-control">
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
                                                           <input id="email_admin" type="checkbox" @if($form->options_emails_admin) checked @endif> <span style="margin-left: 10px;">Email Admin</span>
                                                       </label>
                                                       <input id="email_admin_subject" type="hidden">
                                                       <textarea id="email_admin_message" style="display: none;">123</textarea>
                                                   </div>
                                                </td>
                                                <td><i style="cursor: pointer;" class="fa fa-cog" aria-hidden="true"
                                                    data-email-config="admin"
                                                    data-toggle="modal" data-target="#emailsConfigModal"
                                                    data-backdrop="static" data-keyboard="false"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="checkbox icheck form-group">
                                                        <label style="padding-right: 50px;">
                                                           <input id="email_submitter" type="checkbox" @if($form->options_emails_submitter) checked @endif> <span style="margin-left: 10px;">Email Submitter</span>
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
    <script>
        var redirectTo = '{{route('backend.forms.edit', 0)}}'
        var form_id = {{ $form->id }}

        @include('core.forms.templates.all')

        var options = new Object()
        var actions = new Object()
        var emails = new Object()
        var admin = new Object()
        var submitter = new Object()

        @if(Session::has('form-created'))
            {{ Session::pull('form-created')}}
            feedback("Successfully created the form.", "callout-info", 5000)
        @endif

        admin.subject = `{!! $form->admin_subject !!}`
        admin.message = `{!! $form->admin_message !!}`
        submitter.subject = `{!! $form->submitter_subject !!}`
        submitter.message = `{!! $form->submitter_message !!}`

        emails.admin = admin
        emails.submitter = submitter

        options.emails = emails

        function getFieldTemplate(group_id, field_type, field_id)
        {
            var template = null;
            $('#emptyFormText').hide()
            switch (group_id) {
                case 'common':
                    switch (field_id) {
                        case 'name':
                            template = name_template
                        break;

                        case 'email':
                            template = email_template
                        break;

                        case 'address':
                            template = address_template
                        break;

                        case 'zipcode':
                            template = zipcode_template
                        break;

                        case 'city':
                            template = city_template
                        break;

                        case 'state':
                            template = state_template
                        break;

                        case 'country':
                            template = country_template
                        break;

                        case 'website':
                            template = website_template
                        break;

                        case 'message':
                            template = message_template
                        break;

                        case 'phone':
                            template = phone_template
                        break;
                    }

                break;

                case 'basic':
                    switch (field_type) {
                        case 'text':
                            template = text_template
                        break;

                        case 'checkbox':
                            template = checkbox_template
                        break;

                        case 'select':
                            template = select_template
                        break;

                        case 'textarea':
                            template = textarea_template
                        break;
                    }
                break;
            }
            return template
        }

        function addField(group, db_id, field_id, type, label, required, placeholder, meta) {
            var field = new Object();
            field.group = group
            field.db_id = db_id
            field.field_id = field_id
            field.type = type
            field.label = label
            field.required = required
            field.placeholder = placeholder

            var field_template = getFieldTemplate(field.group, field.type, field.field_id)

            addedFields.push(field.field_id)
            $('#form_editor').append(field_template)

            if(field.group == 'basic') {
                $('#basic_'+field.type).attr('id', field_id)
                $('#'+field_id).find('span').text(field.label)
                $('#'+field_id).data('options', field)

                if(field.type == 'select') {
                    // console.log('meta: ' + meta)
                    if(typeof meta['selectvalues'] != 'undefined')
                        for (var i = 0; i < meta['selectvalues'].length; i++) {
                            // console.log('ole: ' + meta['selectvalues'][i] )
                            var option = new Option( meta['selectvalues'][i] )
                            $(option).html(meta['selectvalues'][i])
                            $('#'+field_id).find('select').append(option)
                            buildSelectValuesTable($('#'+field_id))
                        }
                }
            } else {
                $('#'+field_id).data('options', field)
            }

            $('#'+field_id).data('options', field)
            $('#'+field_id).find('input').attr("placeholder", field.placeholder )

            currentIds.push(field_id)

            if(field.required == false)
                $('#'+field_id).find('required').removeClass('required')
            else
                $('#'+field_id).find('required').addClass('required')
        }

        @foreach ($form->fields as $key => $field)
            @if($field != null)
                group = '{{ $field->group }}'
                db_id = '{{ $field->id }}'
                field_id = '{{ $field->field_id }}'
                type = '{{ $field->type }}'
                label = '{{ $field->label }}'
                required = {{ $field->required }}
                placeholder = '{{ $field->placeholder }}'
                meta = []
                meta['selectvalues'] = []

                @if($field->select_values)
                    @foreach ($field->select_values as $key => $value)
                        meta['selectvalues'][{{$key}}] = '{{$value}}'
                    @endforeach
                @endif

                // console.log('meta1: ' + meta['selectvalues'][0])

                addField(group, db_id, field_id, type, label, required, placeholder, meta)

            @endif
        @endforeach

        displayEmailCodes()

        initFormFields();

        function feedback(title, callout, time) {
            $("#backend-feedback-text").text(title)
            $("#backend-feedback-info").removeClass('callout-info')
            $("#backend-feedback-info").removeClass('callout-danger')
            $("#backend-feedback-info").addClass(callout)
            $("#backend-feedback-info").show().delay(time).fadeOut()
        }

        $("#saveBtn").on("click", function(e) {
            if(canWeSaveTheForm()) {
                var saveBtn = $(this);
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
                $.ajax('{{route('backend.forms.update')}}', {
                    method: 'POST',
                    dataType:'json',
                    data: {
                        meta: formOptions,
                        form_id: form_id,
                        title: $('#title').val(),
                        _token: Laravel.csrfToken
                    },
                    success: function(result) {
                        selected = null
                        updateFieldOptions()
                        feedback("Successfully saved the form.", "callout-info", 4000);
                        $('#form_editor').empty()
                        addedFields = []
                        $.each(result.fields, function( key, field ) {
                            addField(field.group, field.id, field.field_id, field.type, field.label, field.required, field.placeholder, field.meta)
                        })
                        currentIds = []
                        initFormFields()
                        resetICheck()
                        admin.subject = result.form.meta.options.emails.admin.subject
                        submitter.subject = result.form.meta.options.emails.submitter.subject

                        $("#form_editor").addClass("form-success").delay(1000).queue(function(next){
                            $(this).removeClass("form-success");
                            next();
                        });
                        // $("#form_editor").delay(3000).addClass('abcd')
                        // $("#form_editor").delay(32000).removeClass('abcd')

                    },
                    error: function(result) {
                        // enableBtn(saveBtn, 'Save')
                        selected = null
                        updateFieldOptions()
                        var errors = result.responseJSON
                        $("#form_editor").addClass("form-error").delay(10000).queue(function(next){
                            $(this).removeClass("form-error");
                            next();
                        });
                        feedback("There were some errors saving. Not sure why. Contact support.", "callout-danger", 10000);
                    }
                })
            } else {
                feedback("Can't save, each field id must be unique and reserved id's can't be used.", "callout-danger", 4000);
            }
        })

    </script>

@endpush
