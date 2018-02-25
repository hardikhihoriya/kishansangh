@extends('layouts.admin-master')

@section('content')

<section class="content-header">
    <h1>
        {{trans('adminlabels.SHOP_MANAGEMENT')}}
        <small>{{trans('adminlabels.SHOPS')}}</small>
        <div class="pull-right">
            <a href="{{ url('admin/shops/new') }}" class="btn btn-default pull-right-responsive"><span><i class="fa fa-plus-circle"></i>&nbsp;&nbsp;</span>{{trans('adminlabels.ADD_SHOP')}}</a>
        </div>       
    </h1>
</section>

<section class="content">
    <div class="row">        
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">{{trans('adminlabels.SHOP_LIST')}}</h3>
                </div>
                <div class="box-body table-responsive">
                    <table id="listShop" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>{{trans('adminlabels.SHOP_REGISTRATION_NO')}}</th>
                                <th>{{trans('adminlabels.SHOP_OWNER_NAME')}}</th>
                                <th>{{trans('adminlabels.SHOP_VENDOR_NAME')}}</th>
                                <th>{{trans('adminlabels.SHOP_TYPE_NAME')}}</th>
                                <th>{{trans('adminlabels.SHOP_PACKAGE_NAME')}}</th>
                                <th>{{trans('adminlabels.SHOP_NAME')}}</th>
                                <th>{{trans('adminlabels.SHOP_EMAIL')}}</th>
                                <th>{{trans('adminlabels.SHOP_PHONE_NO')}}</th>
                                <th>{{trans('adminlabels.SHOP_ANNIVERSSARY_DATE')}}</th>
                                <th>{{trans('adminlabels.SHOP_ZIPCODE')}}</th>
                                <th>{{trans('adminlabels.SHOP_LOGO')}}</th>
                                <th>{{trans('adminlabels.SHOP_STATUS')}}</th>
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
    var getShopList = function (ajaxParams) {
        $('#listShop').DataTable({
            "processing": true,
            "serverSide": true,
            "destroy": true,
            "ajax": {
                "url": "{{ url('admin/shops/list-ajax') }}",
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
                {"data": "shop_registration_no"},
                {"data": "user_name"},
                {"data": "vendor_name"},
                {"data": "shop_type_name"},
                {"data": "package_name"},
                {"data": "shop_name"},
                {"data": "shop_email"},
                {"data": "shop_phone_no"},
                {"data": "shop_anniversary_date"},
                {"data": "zipcode"},
                {"data": "shop_logo", "orderable": false},
                {"data": "status"},
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
        getShopList(ajaxParams);

        // Remove shop type
        $(document).on('click', '.btn-delete-shop', function (e) {
            e.preventDefault();
            var shopId = $(this).attr('data-id');
            var cmessage = 'Are you sure you want to Delete this Shop ?';
            var ctitle = 'Delete Shop';

            ajaxParams.customActionType = 'groupAction';
            ajaxParams.customActionName = 'delete';
            ajaxParams.id = [shopId];

            bootbox.dialog({
                onEscape: function () {},
                message: cmessage,
                title: ctitle,
                buttons: {
                    Yes: {
                        label: 'Yes',
                        className: 'btn green',
                        callback: function () {
                            getShopList(ajaxParams);
                        }
                    },
                    No: {
                        label: 'No',
                        className: 'btn btn-default'
                    }
                }
            });
        });
        // Change Status
        $(document).on('click', '.btn-status-shop', function (e) {
            e.preventDefault();
            var shopID = $(this).attr('data-id');
            var cmessage  = 'Are you sure you want to Inactive this Shop ?';
            var ctitle  = 'Inactive';

            if ($(this).attr('title')  == 'Make Active' ) {
                cmessage  = 'Are you sure you want to Active this Shop ?';
                ctitle  = 'Active';
            }

            ajaxParams.customActionType = 'groupAction';
            ajaxParams.customActionName = 'status';
            ajaxParams.id = [shopID];

            bootbox.dialog({
                onEscape: function () {},
                message: cmessage,
                title: ctitle,
                buttons: {
                    Yes: {
                        label: 'Yes',
                        className: 'btn green',
                        callback: function () {
                            getShopList(ajaxParams);
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