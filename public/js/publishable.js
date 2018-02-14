
    var publishable = {

        data: {
            status: {
                publish: 1,
                publish_text: 'Publish',
                draft: 2,
                draft_text: 'Draft',
                schedule: 3,
                schedule_text: 'Scheduled',
                immediately: 'Immediately',
                not_yet: 'Not yet',
                min_date: '',
            },
            access: {
                everyone: 1,
                everyone_text: 'Everyone',
                members: 2,
                members_text: 'Members',
                subscribers: 3,
                subscribers_text: 'Paying Members'
            },
            visibility: {
                visible: 'Visible',
                not_visible: 'Not Visible'
            },
            picked_status: 1,
            picked_access: 1,
            publish_date: null,
            password: null
        },

        init: function() {
            var data = this.data
            $('#publish_date').datetimepicker()

            $('#publish_date').on('dp.change', function(e) {
                data.publish_date = $('#publish_date').val();
                // milisecs = moment($('#publish_date').data("DateTimePicker").date()).format('x')
                // formated = moment(milisecs, "x").format("YYYY-MM-DD HH:mm")
                // console.log('pub: '+publish_date)
                // console.log('mom: ' + formated)
            })

            $("#publish_date").blur(function() {
                if($(this).val()) {
                    $('#reset_publish_date').show()
                    if(moment( $('#publish_date').data("DateTimePicker").date() ).isAfter( moment().format("YYYY-MM-D HH:mm") ) ) {
                        data.picked_status = data.status.schedule
                        $('#status').text(data.status.schedule_text)
                        // console.log('status: ' + data.picked_status)
                    } else if(moment( $('#publish_date').data("DateTimePicker").date() ).isBefore( moment().format("YYYY-MM-D HH:mm") ) ) {
                        data.picked_status = data.status.publish
                        $('#status').text(data.status.publish_text)
                    }
                }
            });

            $('#publish_right_now').on('click', function(e) {
                $('#publish_right_now').hide()
                $('#publish_date').show()
                $('#publish_date').data("DateTimePicker").show();
            });

            $('#published_at').on('click', function(e) {
                $('#published_at').hide()
                $('#publish_date').show()
                $('#publish_date').data("DateTimePicker").date($('#publish_date').val())
                // console.log('pub date: ' + $('#publish_date').val())
                $('#publish_date').data("DateTimePicker").show()
            });

            $('#reset_publish_date').on('click', function(e) {
                // console.log('timezone: ' + moment.tz.guess())
                resetStatus()
            });

            function resetStatus() {
                $('#publish_date').hide()
                $('#publish_right_now').show()
                $('#published_at').show()
                // $('#publish_date').data("DateTimePicker").clear();
                $('#reset_publish_date').hide()
                $('#status').text(data.status.publish_text)
                data.picked_status = data.status.publish
            }

            $('#status').on('click', function(e) {
                status = $(this).data('status')

                // console.log('status: '+status)
                // console.log('publishable.data.status.publish_text: '+publishable.data.status.publish_text)

                switch(parseInt(status)) {
                    case data.status.publish:
                        resetStatus()
                        data.picked_status = data.status.draft
                        $(this).data('status', data.status.draft)
                        $('#publish_right_now').text(data.status.not_yet)
                        $('#status').text(data.status.draft_text)
                        $('#visibility').text(data.visibility.not_visible)
                        break;
                    case data.status.schedule:
                        data.picked_status = data.status.draft
                        $(this).data('status', data.status.draft)
                        $('#publish_right_now').text(data.status.not_yet)
                        $('#status').text(data.status.draft_text)
                        $('#visibility').text(data.visibility.not_visible)
                        break;
                    case data.status.draft:
                        resetStatus()
                        data.picked_status = data.status.publish
                        $(this).data('status', data.status.publish)
                        $('#publish_right_now').text(data.status.immediately)
                        $('#status').text(data.status.publish_text)
                        $('#visibility').text(data.visibility.visible)
                        break;
                    default:
                        resetStatus()
                        data.picked_status = data.status.publish
                        $(this).data('status', data.status.publish)
                        $('#publish_right_now').text(data.status.immediately)
                        $('#status').text(data.status.publish_text)
                        $('#visibility').text(data.visibility.visible)
                        break;
                }
            });

            $('#access').on('click', function(e) {
                access = $(this).data('access')

                // console.log('access: '+access)

                switch(parseInt(access)) {
                    case data.access.everyone:
                        data.picked_access = data.access.members
                        $(this).data('access', data.access.members)
                        $('#access').text(data.access.members_text)
                        break;
                    case data.access.members:
                        data.picked_access = data.access.subscribers
                        $(this).data('access', data.access.subscribers)
                        $('#access').text(data.access.subscribers_text)
                        break;
                    case data.access.subscribers:
                        data.picked_access = data.access.everyone
                        $(this).data('access', data.access.everyone)
                        $('#access').text(data.access.everyone_text)
                        break;
                    // default:
                    //     data.picked_access = data.access.everyone
                    //     console.log('picked_access 3: '+data.picked_access)
                    //     $(this).data('access', data.access.everyone)
                    //     $('#access').text(data.access.everyone_text)
                    //     break;
                }
            })
        }
    }

    function feedback(title, callout, time) {
        $("#backend-feedback-text").text(title);
        $("#backend-feedback-info").removeClass('callout-info')
        $("#backend-feedback-info").removeClass('callout-danger')
        $("#backend-feedback-info").addClass(callout)
        $("#backend-feedback-info").show().delay(time).fadeOut()
    }

    function disableBtn(btn, text) {
        btn.text(text);
        btn.prop('disabled', true)
        btn.css('cursor', 'wait')
    }

    function enableBtn(btn, text) {
        btn.text(text);
        btn.prop('disabled', false)
        btn.css('cursor', 'pointer')
    }
