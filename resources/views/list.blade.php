@extends('layouts')
@include('datatables')

@section('content')
    <div class="panel panel-primary">
        <div class="panel-heading text-right">
            <button id="create_event" type="button" class="btn btn-success btn-md"><i class="fa fa-plus"></i> Create
                Event</button>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"> </h4>
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <div id="alert_tmeassage_area"></div>
                    </div>
                    <div class="card-body">
                        {{ $dataTable->table(['width' => '100%']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--     Create edit Event  -->

    <div class="ui tiny modal " id="create_event_modal">
        <i class="close icon"></i>
        <div class="header">
            <h5 class="modal-title event_modal_title">Event</h5>
        </div>
        <div class="scrolling content">
            <form class="ui form" method="post" action="" id="create_event_frm">
                {{ csrf_field() }}
                <input type="hidden" class="form-control" name="id" id="id" value="">
                <div class="field">
                    <input type="text" class="form-control" name="event_title" id="event_title" placeholder="Event name">
                </div>
                <div class="two fields">
                    <div class="field">
                        <div class="ui calendar start" id="start_date" data-id="">
                            <div class="ui input left icon">
                                <i class="calendar icon"></i>
                                <input type="text" name="event_start_date" id="event_start_date" placeholder="Start Date"
                                    value="" class="text-center">
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <div class="ui calendar end" id="end_date" data-id="">
                            <div class="ui input left icon">
                                <i class="calendar icon"></i>
                                <input type="text" name="event_end_date" id="event_end_date" placeholder="End Date"
                                    value="" class="text-center">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <div class="ui fluid selection dropdown" name="recurrence_type" id="recurrence_type">
                        <input type="hidden" class="recurrence_type_value" name="event_recurrence_type"
                            id="event_recurrence_type" value="">
                        <i class="dropdown icon"></i>

                        <div class="default text">Select Recurrence Type</div>
                        <div class="menu">
                            <div class="item" data-value="Single">Single</div>
                            <div class="item" data-value="Daily">Daily</div>
                            <div class="item" data-value="Weekly">Weekly</div>
                            <div class="item" data-value="Monthly">Monthly</div>
                            <div class="item" data-value="Yearly">Yearly</div>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <textarea name="event_description" id="event_description" placeholder="Description"></textarea>
                </div>
                <div class="ui error message"></div>
            </form>
        </div>
        <div class="actions">
            <div class="ui green approve button" id="ajaxSubmit">Save</div>
            <div class="ui cancel button">Cancel</div>
        </div>
    </div>

    <div class="ui mini modal center" id="eventDeleteModal">
        <div class="header">Are you sure, you want to remove this event?</div>
        <div class="content">

        </div>
        <div class="actions">
            <div class="ui positive right labeled icon button yesdeleteBtn" data_id="">Yes<i class="checkmark icon"></i>
            </div>
            <div class="ui negative button">No</div>
        </div>
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
    <script></script>
@endpush

@section('content_script')
    <script>
        var loader = '<img class="loader" src="<?php echo asset('vendor/event/image/ajax-loader.gif'); ?>"/>';
        $(document).ready(function() {
            $('.ui.dropdown').dropdown();
            $('#event_start_date,#event_end_date').flatpickr({
                dateFormat: "Y/m/d",
            });

            $("#create_event").click(function() {
                $("#create_event_frm")[0].reset();
                $("#recurrence_type").dropdown('refresh').dropdown('clear');
                $('#create_event_modal').modal('show');
            });

            $('#create_event_modal').modal({
                autofocus: false,
                closable: false,
                transition: 'fade left',
                onApprove: function() {
                    $('#create_event_frm').trigger('submit');
                    return false;
                }
            });

            $("#create_event_frm").form({
                inline: true,
                fields: {
                    event_title: {
                        identifier: 'event_title',
                        rules: [{
                                type: 'empty',
                                prompt: 'Please enter a event name'
                            },
                            {
                                type: 'maxLength[30]',
                                prompt: 'Please enter at most 30 characters'
                            }
                        ]
                    },
                    event_recurrence_type: {
                        identifier: 'event_recurrence_type',
                        rules: [{
                            type: 'empty',
                            prompt: 'Select event recurrence type'
                        }, ]
                    },
                    event_description: {
                        identifier: 'event_description',
                        rules: [{
                            type: 'maxLength[2000]',
                            prompt: 'Please enter at most 2000 characters'
                        }, ]
                    },
                },

                onSuccess: function(e) {
                    e.preventDefault();
                    $('#create_event_alert').show().html(loader);

                    var action = "{{ route('event.store') }}";
                    var formData = new FormData($('#create_event_frm')[0]);
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        url: action,
                        data: formData,
                        contentType: false,
                        processData: false,
                        async: false,
                        success: function(feedback) {
                            var jd = $.parseJSON(feedback);

                            if (jd.type == 'alert-success') {
                                $("#create_event_frm")[0].reset();
                                $('#create_event_modal').modal('hide');
                                $('#create_event_alert').show().html('');

                                $('#alert_tmeassage_area').show().html(
                                    '<div class="alert ' + jd.type +
                                    '"><a href="" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
                                    jd.message + '</div>');
                            } else {
                                var msg = '';
                                $.each(jd.error, function(key, value) {
                                    msg += value + '</br>';
                                });

                                $('#create_event_alert').show().html('<div class="alert ' +
                                    jd.type +
                                    '"><a href="" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
                                    msg + '</div>');
                            }
                            $('.dataTable').DataTable().ajax.reload();
                        }
                    });
                }
            });

            function resetForm() {
                $("#create_event_frm").form('reset');
                $("#id").val('');
                $("#recurrence_type").dropdown('refresh').dropdown('clear');
                $('.event_modal_title').text("Add Event");
            }

            $(document).on('click', '.show_btn, .edit_btn', function(e) {
                e.preventDefault();
                resetForm();
                $('#ajaxSubmit').show();
                if ($(this).hasClass('edit_btn')) {
                    $('.event_modal_title').text("Edit Event");
                } else {
                    $('.event_modal_title').text("View Event");
                    $('#ajaxSubmit').hide();
                }
                var temp = $(this).data('id');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ url('event') }}/" + temp,
                    success: function(data) {
                        console.log(data);
                        $('#id').val(data.id);
                        $('#event_title').val(data.event_title);
                        $('#event_description').val(data.event_description);
                        $('#recurrence_type').dropdown('set selected', data
                            .event_recurrence_type);
                        $('#event_start_date').val(data.event_start_date);
                        $('#event_end_date').val(data.event_end_date);
                        $('#create_event_modal').modal('show');
                    }
                });
            });

            $(document).on('click', ".delete_btn", function(e) {
                e.preventDefault();
                $("#eventDeleteModal").modal('show');
                var id = $(this).data('id');
                $(".yesdeleteBtn").attr('data_id', id);
            });

            $(document).on('click', '.yesdeleteBtn', function() {
                var id = $(this).attr('data_id');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ url('event') }}/" + id,
                    method: "DELETE",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function(data) {
                        var jd = $.parseJSON(data);

                        if (jd.type == 'alert-success') {
                            $('#eventDeleteModal').modal('hide');

                            $('#alert_tmeassage_area').show().html(
                                '<div class="alert ' + jd.type +
                                '"><a href="" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
                                jd.message + '</div>');
                        }

                        $('.dataTable').DataTable().ajax.reload();
                    }
                });
            });
        });
    </script>
@endsection
