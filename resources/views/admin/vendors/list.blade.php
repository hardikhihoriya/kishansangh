@extends('layouts.admin-master')

@section('content')

<section class="content-header">
    <h1>
        {{trans('adminlabels.VENDOR_MANAGEMENT')}}
        <small>{{trans('adminlabels.VENDORS')}}</small>
        <div class="pull-right">
            <a href="{{ url('admin/vendor/new') }}" class="btn btn-default pull-right-responsive"><span><i class="fa fa-plus-circle"></i>&nbsp;&nbsp;</span>{{trans('adminlabels.ADD_VENDOR')}}</a>
        </div>       
    </h1>
</section>

<section class="content">
    <div class="row">        
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">{{trans('adminlabels.VENDOR_LIST')}}</h3>
                </div>
                <div class="box-body">
                    <table id="listVendor" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>{{trans('adminlabels.USER_UNIQUE_NO')}}</th>
                                <th>{{trans('adminlabels.USER_NAME')}}</th>
                                <th>{{trans('adminlabels.USER_EMAIL')}}</th>
                                <th>{{trans('adminlabels.USER_PHONE')}}</th>
                                <th>{{trans('adminlabels.USER_BIRTH_DAY')}}</th>
                                <th>{{trans('adminlabels.USER_GENDER')}}</th>
                                <th>{{trans('adminlabels.VENDOR_NAME')}}</th>
                                <th>{{trans('adminlabels.ACTION')}}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>

        </div>
        <!--/.col (right) -->
    </div>
    <!-- /.row -->
</section>
@endsection
@section('script')

<script>
    var getVendorList = function (ajaxParams) {
        $('#listVendor').DataTable({
            "processing": true,
            "serverSide": true,
            "destroy": true,
            "ajax": {
                "url": "{{ url('admin/vendor/list-ajax') }}",
                "dataType": "json",
                "type": "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                "data": function (data) {
                    if (ajaxParams) {
                        $.each(ajaxParams, function (key, value) {
                            data[key] = value;
                        });
                        ajaxParams = {};
                    }
                }
            },
            "columns": [
                {"data": "registration_no"},
                {"data": "name"},
                {"data": "email"},
                {"data": "phone_no"},
                {"data": "birth_date"},
                {"data": "gender"},
                {"data": "vendor_name"},
                {"data": "action", "orderable": false}
            ],
            "initComplete": function (settings, json) {
                if (typeof (json.customMessage) != "undefined" && json.customMessage !== '') {
                    $('.success-msg').addClass('hidden');
                    $('.error-msg').addClass('hidden');
                    $('.customMessage').removeClass('hidden');
                    $('#customMessage').html(json.customMessage);
                }
            }
        });
    };
    $(document).ready(function () {
        var ajaxParams = {};
        getVendorList(ajaxParams);

        // Remove user
        $(document).on('click', '.btn-delete-vendor', function (e) {
            e.preventDefault();
            var vendorId = $(this).attr('data-id');
            var cmessage = 'Are you sure you want to Delete this Vendor ?';
            var ctitle = 'Delete Vendor';

            ajaxParams.customActionType = 'groupAction';
            ajaxParams.customActionName = 'delete';
            ajaxParams.id = [vendorId];

            bootbox.dialog({
                onEscape: function () {},
                message: cmessage,
                title: ctitle,
                buttons: {
                    Yes: {
                        label: 'Yes',
                        className: 'btn green',
                        callback: function () {
                            getVendorList(ajaxParams);
                        }
                    },
                    No: {
                        label: 'No',
                        className: 'btn btn-default'
                    }
                }
            });
        });
    });
</script>
@endsection