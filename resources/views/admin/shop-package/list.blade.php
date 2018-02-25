@extends('layouts.admin-master')

@section('content')

<section class="content-header">
    <h1>
        {{trans('adminlabels.SHOP_PACKAGE_MANAGEMENT')}}
        <small>{{trans('adminlabels.SHOP_PACKAGES')}}</small>
        <div class="pull-right">
            <a href="{{ url('admin/shop-package/new') }}" class="btn btn-default pull-right-responsive"><span><i class="fa fa-plus-circle"></i>&nbsp;&nbsp;</span>{{trans('adminlabels.ADD_SHOP_PACKAGE')}}</a>
        </div>       
    </h1>
</section>

<section class="content">
    <div class="row">        
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">{{trans('adminlabels.SHOP_PACKAGE_LIST')}}</h3>
                </div>
                <div class="box-body table-responsive">
                    <table id="listShopPackage" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>{{trans('adminlabels.SHOP_PACKAGE_MARKETING_NAME')}}</th>
                                <th>{{trans('adminlabels.SHOP_PACKAGE_NAME')}}</th>
                                <th>{{trans('adminlabels.SHOP_PACKAGE_BOOSTING_POINT')}}</th>
                                <th>{{trans('adminlabels.SHOP_PACKAGE_PER_DAY_SMS')}}</th>
                                <th>{{trans('adminlabels.SHOP_PACKAGE_DESCRIPTION')}}</th>
                                <th>{{trans('adminlabels.SHOP_PACKAGE_PRICE')}}</th>
                                <th>{{trans('adminlabels.SHOP_PACKAGE_STATUS')}}</th>
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
    var getShopPackageList = function (ajaxParams) {
        $('#listShopPackage').DataTable({
            "processing": true,
            "serverSide": true,
            "destroy": true,
            "ajax": {
                "url": "{{ url('admin/shop-package/list-ajax') }}",
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
                {"data": "shop_marketing_name"},
                {"data": "package_name"},
                {"data": "boosting_point"},
                {"data": "per_day_SMS"},
                {"data": "package_description"},
                {"data": "price"},
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
        getShopPackageList(ajaxParams);

        // Remove shop type
        $(document).on('click', '.btn-delete-shop-package', function (e) {
            e.preventDefault();
            var shopMarketingId = $(this).attr('data-id');
            var cmessage = 'Are you sure you want to Delete this Shop Package ?';
            var ctitle = 'Delete Shop Package';

            ajaxParams.customActionType = 'groupAction';
            ajaxParams.customActionName = 'delete';
            ajaxParams.id = [shopMarketingId];

            bootbox.dialog({
                onEscape: function () {},
                message: cmessage,
                title: ctitle,
                buttons: {
                    Yes: {
                        label: 'Yes',
                        className: 'btn green',
                        callback: function () {
                            getShopPackageList(ajaxParams);
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
        $(document).on('click', '.btn-status-shop-package', function (e) {
            e.preventDefault();
            var shopPackageID = $(this).attr('data-id');
            var cmessage  = 'Are you sure you want to Inactive this Shop Package ?';
            var ctitle  = 'Inactive';

            if ($(this).attr('title')  == 'Make Active' ) {
                cmessage  = 'Are you sure you want to Active this Shop Package ?';
                ctitle  = 'Active';
            }

            ajaxParams.customActionType = 'groupAction';
            ajaxParams.customActionName = 'status';
            ajaxParams.id = [shopPackageID];

            bootbox.dialog({
                onEscape: function () {},
                message: cmessage,
                title: ctitle,
                buttons: {
                    Yes: {
                        label: 'Yes',
                        className: 'btn green',
                        callback: function () {
                            getShopPackageList(ajaxParams);
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