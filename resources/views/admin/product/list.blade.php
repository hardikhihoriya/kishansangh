@extends('layouts.admin-master')

@section('content')

<section class="content-header">
    <h1>
        {{trans('adminlabels.PRODUCT_MANAGEMENT')}}
        <small>{{trans('adminlabels.PRODUCTS')}}</small>
        <div class="pull-right">
            <a href="{{ url('admin/product/new') }}" class="btn btn-default pull-right-responsive"><span><i class="fa fa-plus-circle"></i>&nbsp;&nbsp;</span>{{trans('adminlabels.ADD_PRODUCT')}}</a>
        </div>       
    </h1>
</section>

<section class="content">
    <div class="row">        
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">{{trans('adminlabels.PRODUCT_LIST')}}</h3>
                </div>
                <div class="box-body table-responsive">
                    <table id="listProduct" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>{{trans('adminlabels.PRODUCT_NAME')}}</th>
                                <th>{{trans('adminlabels.PRODUCT_TYPE')}}</th>
                                <th>{{trans('adminlabels.PRODUCT_YEAR')}}</th>
                                <th>{{trans('adminlabels.PRODUCT_STATUS')}}</th>
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
    var getProductList = function (ajaxParams) {
        $('#listProduct').DataTable({
            "processing": true,
            "serverSide": true,
            "destroy": true,
            "ajax": {
                "url": "{{ url('admin/product/list-ajax') }}",
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
                {"data": "product_name"},
                {"data": "product_type"},
                {"data": "product_year"},
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
        getProductList(ajaxParams);

        // Remove shop type
        $(document).on('click', '.btn-delete-product', function (e) {
            e.preventDefault();
            var productId = $(this).attr('data-id');
            var cmessage = 'Are you sure you want to Delete this Product ?';
            var ctitle = 'Delete Product';

            ajaxParams.customActionType = 'groupAction';
            ajaxParams.customActionName = 'delete';
            ajaxParams.id = [productId];

            bootbox.dialog({
                onEscape: function () {},
                message: cmessage,
                title: ctitle,
                buttons: {
                    Yes: {
                        label: 'Yes',
                        className: 'btn green',
                        callback: function () {
                            getProductList(ajaxParams);
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
        $(document).on('click', '.btn-status-product', function (e) {
            e.preventDefault();
            var productID = $(this).attr('data-id');
            var cmessage  = 'Are you sure you want to Inactive this Product ?';
            var ctitle  = 'Inactive';

            if ($(this).attr('title')  == 'Make Active' ) {
                cmessage  = 'Are you sure you want to Active this Product ?';
                ctitle  = 'Active';
            }

            ajaxParams.customActionType = 'groupAction';
            ajaxParams.customActionName = 'status';
            ajaxParams.id = [productID];

            bootbox.dialog({
                onEscape: function () {},
                message: cmessage,
                title: ctitle,
                buttons: {
                    Yes: {
                        label: 'Yes',
                        className: 'btn green',
                        callback: function () {
                            getProductList(ajaxParams);
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