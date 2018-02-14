var form = null;
var addedFields = [];
var fieldObjects = [];
var selectedField = null;
var groupSelected = null;
var canSave = true;
var protectedIds = [ "name", "email", "message", 'address', 'city', 'zip', 'state', 'country', 'phone', 'website', 'basic_text', 'basic_textarea', 'basic_select', 'basic_checkbox']
var currentIds = []
var selected_option_text = null
var select_values = []

    $('#required').on('ifChecked', function(event){
        if(selectedField) {
            selectedField.data('options').required = true
            selectedField.find('required').addClass('required')
        }
    });

    $('#required').on('ifUnchecked', function(event){
        if(selectedField) {
            selectedField.data('options').required = false
            selectedField.find('required').removeClass('required')
        }
    });

    $('#editLabel').on('keyup', function() {
        // console.log("title");
        selectedField.find('span').text($('#editLabel').val())
        selectedField.data('options').label = $('#editLabel').val()
    });

    $('#fieldPlaceholder').on('keyup', function() {
        selectedField.find('input').attr("placeholder", $('#fieldPlaceholder').val())
        selectedField.data('options').placeholder = $('#fieldPlaceholder').val()
    });


    function buildSelectValuesTable(field = null) {

        if(!field)
            field = selectedField

        $('#select_value_input').val('')

        select_values[field.attr('id')] = []

        field.find("select option").each(function(i) {
            select_values[field.attr('id')][i] = $(this).text()
        })

        field.data('options').selectvalues = select_values[field.attr('id')]

        $('#select_added_values').empty()

        var values = field.data('options').selectvalues

        for (var key in values) {
            if (values.hasOwnProperty(key)) {
                var value_markup = "<tr><td style='cursor: pointer; width: 100px;' class='option_value_text'>"+ values[key] +"</td> <td><i style='margin-left: 20px; cursor: pointer;' class='fa fa-times remove_select_value'></i></td></tr>"
                $('#select_added_values').append(value_markup)
            }
        }

        initRemoveSelectValue()
    }

    function initRemoveSelectValue()
    {
        $('.remove_select_value').unbind()
        $('.remove_select_value').on('click', function() {
            // console.log('removed: ' + $(this).parent().prev().text())
            selectedField.find('select option:contains('+ $(this).parent().prev().text() +')').remove()
            buildSelectValuesTable()
            // console.log('values: ' + selectedField.data('options').selectvalues)
        })
    }

    $('#add_select_value_btn').on('click', function() {
        var o = new Option( $('#select_value_input').val() )
        $(o).html($('#select_value_input').val())

        if($('#add_select_value_btn').attr('data-action') == 'add') {
            selectedField.find('select').append(o)

            buildSelectValuesTable()

            // console.log('values: ' + selectedField.data('options').selectvalues)
        } else if($('#add_select_value_btn').attr('data-action') == 'update') {

            selectedField.find('select option:contains('+selected_option_text+')').text( $('#select_value_input').val() );

            buildSelectValuesTable()

            $('#add_select_value_btn').text('Add')
            $('#add_select_value_btn').attr('data-action', 'add')

            // console.log('values: ' + selectedField.data('options').selectvalues)
        }

        $('.option_value_text').unbind()
        $('.option_value_text').on('click', function() {
            $('#select_value_input').val($(this).text())
            selected_option_text = $(this).text()
            selected_option_td = $(this)
            $('#add_select_value_btn').text('Update')
            $('#add_select_value_btn').attr('data-action', 'update')
        })

        initRemoveSelectValue()

        selectedField.find('select option:contains("option text")').text('newtext')
    })

    function checkForUniqueId() {
        warningIdNotUnique(false)
        if(selectedField) {
            $('#form_editor').find('.selectable').each(function(i, obj) {
                if( selectedField.attr('id') != $(obj).attr('id') ) {
                    if( $('#fieldId').val() == $(obj).data('options').field_id) {
                        // notUnique.push(selectedField.attr('id'))
                        warningIdNotUnique(true)
                        return false;
                    }
                } else if(protectedIds.includes($('#fieldId').val()) && selectedField.data('options').group != 'common') {
                    // notUnique.push(selectedField.attr('id'))
                    warningIdNotUnique(true)
                }
            })
        }
    }



    $('#fieldId').on('keyup', function() {

        checkForUniqueId()

        selectedField.data('options').field_id = $('#fieldId').val()

        displayEmailCodes()

        canWeSaveTheForm()

    });

    function canWeSaveTheForm() {
        if(hasDuplicates(currentIds)) {
            return false
            console.log('duplicates')
        }
        else {
            console.log('no duplicates')
            return true
        }
    }

    function hasDuplicates(array) {
        return (new Set(array)).size !== array.length;
    }

    function warningIdNotUnique(state) {
        if(state) {
            $('#fieldId').addClass('warning')
            $('#must_be_unique').addClass('warning')
        } else {
            $('#fieldId').removeClass('warning')
            $('#must_be_unique').removeClass('warning')
        }
    }

    function updateFieldOptions(selected = null)
    {
        warningIdNotUnique(false)

        if(selected) {
            $('#label').text(selected.data('options').label)
            if(selected.data('options').required)
                $('#required').iCheck('check');
            else
                $('#required').iCheck('uncheck');

            $('#fieldOptions').show()
            $('#fieldOptionsEmpty').hide()
            $('#select_value_input').closest('tr').hide()

            if(groupSelected == 'common') {
                $('#editLabel').hide()
                $('#label').show()
                $('#label').show()
                $("#fieldId").prop('disabled', true);
                $('#fieldPlaceholder').prop('disabled', true);
                $('#fieldId').val(selectedField.data('options').field_id)
                $('#fieldPlaceholder').val(selectedField.data('options').placeholder)
                $('#fieldPlaceholder').closest('tr').show()
            }
            else {
                $('#editLabel').show()
                $('#editLabel').val(selectedField.data('options').label)
                $('#fieldPlaceholder').val(selectedField.data('options').placeholder)
                $('#label').hide()
                $("#fieldId").prop('disabled', false)

                var no_placeholder = ["select", "checkbox"]

                if(no_placeholder.indexOf( selected.data('options').type ) > -1) {
                    $('#fieldPlaceholder').closest('tr').hide()
                    if(selected.data('options').type == 'select')
                        $('#select_value_input').closest('tr').show()
                }
                else {
                    $('#select_value_input').closest('tr').hide()
                    $('#fieldPlaceholder').prop('disabled', false)
                    $('#fieldPlaceholder').closest('tr').show()
                }

                if(selected.data('options').type == 'select') {
                    $('#select_value_input').closest('tr').show()
                    buildSelectValuesTable()
                }

                $('#fieldId').val(selectedField.data('options').field_id)
            }

            if(selectedField != null) {

                $('#form_editor').find('.selectable').each(function(i, obj) {
                    if( selectedField.attr('id') != $(obj).attr('id') ) {
                        if( $('#fieldId').val() == $(obj).data('options').field_id ) {
                            warningIdNotUnique(true)
                        }
                    }
                });

            }

        }
        else {
            $('#fieldOptions').hide()
            $('#fieldOptionsEmpty').show()

            $('#label').text('')
            $('#required').iCheck('uncheck');
            $('#required').iCheck('disable');
        }

        checkForUniqueId()
    }

    function initFormFields(select = null)
    {
        $('#form_editor').find('.selectable').unbind();
        $('#form_editor').find('.selectable').on("click", function(e) {

            $('#required').iCheck('enable');
            clickedField = $(e.target).closest('.selectable');
            // console.log('clicked: ' + clickedField.text())
            selectedField = clickedField

            groupSelected = selectedField.data('options').group
            // console.log('groupSelected: ' +groupSelected)
            updateFieldOptions(clickedField)

            $('#form_editor').find('.selectable').each(function(i, obj) {
                $(obj).removeClass('selected-field')
            });

            $(clickedField).addClass('selected-field')

            $('.nav-tabs a[href="#tabFieldOptions"]').tab('show');
        })

        $('#form_editor').find('.btn-box-tool').unbind()
        $('#form_editor').find('.btn-box-tool').on('click', function(event) {
            var id = $(event.target).closest('.selectable').data('options').field_id
            var i = addedFields.indexOf(id);
            if(i != -1) {
                addedFields.splice(i, 1);
            }

            $('#required').iCheck('uncheck');
            $('#required').iCheck('disable');

            $(event.target).closest('.selectable').remove()

            displayEmailCodes()

            selectedField = null
            updateFieldOptions()
            $('#label').text('')
        })

        if(select) {
            $('#form_editor').find('.selectable').each(function(i, obj) {
                $(obj).removeClass('selected-field')
            });
            $(select).addClass('selected-field')
        }

        // console.log('order: ' + form.toArray())
    }

    function displayEmailCodes()
    {
        currentIds = []
        $('#form_editor').find('.selectable').each(function(i, field) {
            currentIds.push($(field).data('options').field_id)
        })

        $('#codes').empty()
        for (var i = 0; i < currentIds.length; i++) {
            $('#codes').append('['+currentIds[i]+']')
        }
    }

    form = Sortable.create(form_editor, {
        group: {name: 'form'},

        onAdd: function (evt) {

            var field_id = $(evt.clone).data("id")
            var field_label = $(evt.clone).data("label")
            var group = $(evt.clone).data("group")
            var field_type = $(evt.clone).data("type")
            var d = new Date()

            var double = false

            $('#emptyFormText').hide()

            if(addedFields.includes(field_id) && group == 'common') {
                field_id = 'default'
            }

            switch (field_id) {
                case 'basic_text':
                    $(evt.item).replaceWith( text_template );
                    addedFields.push(field_id)
                    var field = new Object()
                    field.group = group
                    field.field_id = field_id+'_'+d.getTime()
                    field.type = field_type
                    field.label = field_label
                    field.placeholder = $('#basic_text').find('.form-control').attr("placeholder")
                    field.required = false
                    $('#basic_text').data('options', field)
                    $('#basic_text').attr('id', field.field_id)
                break;

                case 'basic_textarea':
                    $(evt.item).replaceWith( textarea_template );
                    addedFields.push(field_id)
                    var field = new Object()
                    field.group = group
                    field.field_id = field_id+'_'+d.getTime()
                    console.log('id '+field.field_id)
                    field.type = field_type
                    field.label = field_label
                    field.placeholder = $('#basic_textarea').find('.form-control').attr("placeholder")
                    field.required = false
                    $('#basic_textarea').data('options', field)
                    $('#basic_textarea').attr('id', field.field_id)
                break;

                case 'basic_checkbox':
                    $(evt.item).replaceWith( checkbox_template );
                    addedFields.push(field_id)
                    var field = new Object()
                    field.group = group
                    field.field_id = field_id+'_'+d.getTime()
                    field.type = field_type
                    field.label = field_label
                    field.placeholder = ''
                    field.required = false
                    $('#basic_checkbox').data('options', field)
                    $('#basic_checkbox').attr('id', field.field_id)
                break;

                case 'basic_select':
                    $(evt.item).replaceWith( select_template );
                    addedFields.push(field_id)
                    var field = new Object()
                    field.group = group
                    field.field_id = field_id+'_'+d.getTime()
                    field.type = field_type
                    field.label = field_label
                    field.placeholder = ''
                    field.required = false
                    console.log('group: ' +field.group)
                    $('#basic_select').data('options', field)
                    console.log('options: ' + $('#basic_select').data('options').group )
                    $('#basic_select').attr('id', field.field_id)
                    select_values[field.field_id] = []
                break;

                case 'name':
                    $(evt.item).replaceWith( name_template );
                    addedFields.push(field_id)
                    var field = new Object();
                    field.group = group
                    field.field_id = field_id
                    field.type = field_type
                    field.label = field_label
                    field.placeholder = $('#name').find('.form-control').attr("placeholder")
                    field.required = true
                    $('#name').data('options', field)
                break;

                case 'email':
                    $(evt.item).replaceWith( email_template );
                    addedFields.push(field_id)
                    var field = new Object();
                    field.group = group
                    field.field_id = field_id
                    field.type = field_type
                    field.label = field_label
                    field.placeholder = $('#email').find('.form-control').attr("placeholder")
                    field.required = true
                    fieldObjects.push(field)
                    $('#email').data('options', field)
                break;

                case 'address':
                    $(evt.item).replaceWith( address_template );
                    addedFields.push(field_id)
                    var field = new Object();
                    field.group = group
                    field.field_id = field_id
                    field.type = field_type
                    field.label = field_label
                    field.placeholder = $('#address').find('.form-control').attr("placeholder")
                    field.required = false
                    fieldObjects.push(field)
                    $('#address').data('options', field)
                break;

                case 'zipcode':
                    $(evt.item).replaceWith( zipcode_template );
                    addedFields.push(field_id)
                    var field = new Object();
                    field.group = group
                    field.field_id = field_id
                    field.type = field_type
                    field.label = field_label
                    field.placeholder = $('#zipcode').find('.form-control').attr("placeholder")
                    field.required = false
                    fieldObjects.push(field)
                    $('#zipcode').data('options', field)
                break;

                case 'city':
                    $(evt.item).replaceWith( city_template );
                    addedFields.push(field_id)
                    var field = new Object();
                    field.group = group
                    field.field_id = field_id
                    field.type = field_type
                    field.label = field_label
                    field.placeholder = $('#city').find('.form-control').attr("placeholder")
                    field.required = false
                    fieldObjects.push(field)
                    $('#city').data('options', field)
                break;

                case 'state':
                    $(evt.item).replaceWith( state_template );
                    addedFields.push(field_id)
                    var field = new Object();
                    field.group = group
                    field.field_id = field_id
                    field.type = field_type
                    field.label = field_label
                    field.placeholder = $('#state').find('.form-control').attr("placeholder")
                    field.required = false
                    fieldObjects.push(field)
                    $('#state').data('options', field)
                break;

                case 'country':
                    $(evt.item).replaceWith( country_template );
                    addedFields.push(field_id)
                    var field = new Object();
                    field.group = group
                    field.field_id = field_id
                    field.type = field_type
                    field.label = field_label
                    field.placeholder = $('#country').find('.form-control').attr("placeholder")
                    field.required = false
                    fieldObjects.push(field)
                    $('#country').data('options', field)
                break;

                case 'website':
                    $(evt.item).replaceWith( website_template );
                    addedFields.push(field_id)
                    var field = new Object();
                    field.group = group
                    field.field_id = field_id
                    field.type = field_type
                    field.label = field_label
                    field.placeholder = $('#website').find('.form-control').attr("placeholder")
                    field.required = false
                    fieldObjects.push(field)
                    $('#website').data('options', field)
                break;

                case 'message':
                    $(evt.item).replaceWith( message_template );
                    addedFields.push(field_id)
                    var field = new Object();
                    field.group = group
                    field.field_id = field_id
                    field.type = field_type
                    field.label = field_label
                    field.placeholder = $('#message').find('.form-control').attr("placeholder")
                    field.required = true
                    fieldObjects.push(field)
                    $('#message').data('options', field)
                break;

                case 'phone':
                    $(evt.item).replaceWith( phone_template );
                    addedFields.push(field_id)
                    var field = new Object();
                    field.group = group
                    field.field_id = field_id
                    field.type = field_type
                    field.label = field_label
                    field.placeholder = $('#phone').find('.form-control').attr("placeholder")
                    field.required = false
                    fieldObjects.push(field)
                    $('#phone').data('options', field)
                break;

                case 'default':
                    $(evt.item).remove();
                    $("#field-feedback-text").text("This field can only be added once.");
                    $("#field-feedback-info").show().delay(3500).fadeOut();
                    double = true
                break;
            }

            if(!double) {
                displayEmailCodes()

                selectedField = null

                $('#form_editor').find('.selectable').each(function(i, obj) {
                    $(obj).removeClass('selected-field')
                });
                resetICheck()
                displayEmailCodes()
                initFormFields()
                updateFieldOptions()
            }
        }
    });

    function resetICheck()
    {
        $('.selectable input').iCheck('destroy')

        $('.selectable input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '10%' // optional
        });
    }

    initFormFields();

    Sortable.create(user_fields, {
        group: {name: 'form', pull: 'clone', put: 'true'},
        sort: false,
        onClone: function (evt) {
            // $(evt.item).css('border', '2px solid red')
            // $(evt.item).css('width', '450px')
            // $(evt.item).css('background', 'blue')
            // $(evt.item).removeClass('form-field')
            // $(evt.item).addClass('form-test')
            // $(evt.item).html('<b> Name GHOST </b>')
        }
    });

    Sortable.create(basic_fields, {
        group: {name: 'form', pull: 'clone', put: 'true'},
        sort: false,
        onClone: function (evt) {
            // $(evt.item).css('border', '2px solid red')
            // $(evt.item).css('width', '450px')
            // $(evt.item).css('background', 'blue')
            // $(evt.item).removeClass('form-field')
            // $(evt.item).addClass('form-test')
            // $(evt.item).html('<b> Name GHOST </b>')
        }
    });
