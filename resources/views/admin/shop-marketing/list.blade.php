@extends('layouts.admin-master')

@section('content')

<section class="content-header">
    <h1>
        {{trans('adminlabels.SHOP_MARKETING_MANAGEMENT')}}
        <small>{{trans('adminlabels.SHOP_MARKETINGS')}}</small>
        <div class="pull-right">
            <a href="{{ url('admin/shop-marketing/new') }}" class="btn btn-default pull-right-responsive"><span><i class="fa fa-plus-circle"></i>&nbsp;&nbsp;</span>{{trans('adminlabels.ADD_SHOP_MARKETING')}}</a>
        </div>       
    </h1>
</section>

<section class="content">
    <div class="row">        
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">{{trans('adminlabels.SHOP_MARKETING_LIST')}}</h3>
                </div>
                <div class="box-body">
                    <table id="listShopMarketing" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>{{trans('adminlabels.SHOP_MARKETING_NAME')}}</th>
                                <th>{{trans('adminlabels.SHOP_MARKETING_MEMBER')}}</th>
                                <th>{{trans('adminlabels.SHOP_MARKETING_DESCRIPTION')}}</th>
                                <th>{{trans('adminlabels.SHOP_MARKETING_STATUS')}}</th>
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
    var getShopMarketingList = function (ajaxParams) {
        $('#listShopMarketing').DataTable({
            "processing": true,
            "serverSide": true,
            "destroy": true,
            "ajax": {
                "url": "{{ url('admin/shop-marketing/list-ajax') }}",
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
                {"data": "member"},
                {"data": "description"},
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
        getShopMarketingList(ajaxParams);

        // Remove shop type
        $(document).on('click', '.btn-delete-shop-marketing', function (e) {
            e.preventDefault();
            var shopMarketingId = $(this).attr('data-id');
            var cmessage = 'Are you sure you want to Delete this Shop Marketing ?';
            var ctitle = 'Delete Shop Marketing';

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
                            getShopMarketingList(ajaxParams);
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
        $(document).on('click', '.btn-status-shop-marketing', function (e) {
            e.preventDefault();
            var shopMarketingID = $(this).attr('data-id');
            var cmessage  = 'Are you sure you want to Inactive this Shop Marketing ?';
            var ctitle  = 'Inactive';

            if ($(this).attr('title')  == 'Make Active' ) {
                cmessage  = 'Are you sure you want to Active this Shop Marketing ?';
                ctitle  = 'Active';
            }

            ajaxParams.customActionType = 'groupAction';
            ajaxParams.customActionName = 'status';
            ajaxParams.id = [shopMarketingID];

            bootbox.dialog({
                onEscape: function () {},
                message: cmessage,
                title: ctitle,
                buttons: {
                    Yes: {
                        label: 'Yes',
                        className: 'btn green',
                        callback: function () {
                            getShopMarketingList(ajaxParams);
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