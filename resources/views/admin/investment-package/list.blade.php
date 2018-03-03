@extends('layouts.admin-master')

@section('content')

<section class="content-header">
    <h1>
        {{trans('adminlabels.INVESTMENT_PACKAGE_MANAGEMENT')}}
        <small>{{trans('adminlabels.INVESTMENT_PACKAGES')}}</small>
        <div class="pull-right">
            <a href="{{ url('admin/investment-package/new') }}" class="btn btn-default pull-right-responsive"><span><i class="fa fa-plus-circle"></i>&nbsp;&nbsp;</span>{{trans('adminlabels.ADD_INVESTMENT_PACKAGE')}}</a>
        </div>       
    </h1>
</section>

<section class="content">
    <div class="row">        
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">{{trans('adminlabels.INVESTMENT_PACKAGE_LIST')}}</h3>
                </div>
                <div class="box-body table-responsive">
                    <table id="listInvestmentPackage" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>{{trans('adminlabels.INVESTMENT_PACKAGE_NAME')}}</th>
                                <th>{{trans('adminlabels.INVESTMENT_PACKAGE_YEAR')}}</th>
                                <th>{{trans('adminlabels.INVESTMENT_PACKAGE_SIZE')}}</th>
                                <th>{{trans('adminlabels.INVESTMENT_PACKAGE_DURATION')}}</th>
                                <th>{{trans('adminlabels.INVESTMENT_PACKAGE_PRICE')}}</th>
                                <th>{{trans('adminlabels.INVESTMENT_PACKAGE_STATUS')}}</th>
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
    var getInvestmentPackageList = function (ajaxParams) {
        $('#listInvestmentPackage').DataTable({
            "processing": true,
            "serverSide": true,
            "destroy": true,
            "ajax": {
                "url": "{{ url('admin/investment-package/list-ajax') }}",
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
                {"data": "investment_package_name"},
                {"data": "investment_package_year"},
                {"data": "investment_package_size"},
                {"data": "investment_package_duration"},
                {"data": "investment_package_price"},
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
        getInvestmentPackageList(ajaxParams);

        // Remove shop type
        $(document).on('click', '.btn-delete-investment-package', function (e) {
            e.preventDefault();
            var investmentPackageId = $(this).attr('data-id');
            var cmessage = 'Are you sure you want to Delete this InvestmentPackage ?';
            var ctitle = 'Delete InvestmentPackage';

            ajaxParams.customActionType = 'groupAction';
            ajaxParams.customActionName = 'delete';
            ajaxParams.id = [investmentPackageId];

            bootbox.dialog({
                onEscape: function () {},
                message: cmessage,
                title: ctitle,
                buttons: {
                    Yes: {
                        label: 'Yes',
                        className: 'btn green',
                        callback: function () {
                            getInvestmentPackageList(ajaxParams);
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
        $(document).on('click', '.btn-status-investment-package', function (e) {
            e.preventDefault();
            var investmentPackageID = $(this).attr('data-id');
            var cmessage  = 'Are you sure you want to Inactive this InvestmentPackage ?';
            var ctitle  = 'Inactive';

            if ($(this).attr('title')  == 'Make Active' ) {
                cmessage  = 'Are you sure you want to Active this InvestmentPackage ?';
                ctitle  = 'Active';
            }

            ajaxParams.customActionType = 'groupAction';
            ajaxParams.customActionName = 'status';
            ajaxParams.id = [investmentPackageID];

            bootbox.dialog({
                onEscape: function () {},
                message: cmessage,
                title: ctitle,
                buttons: {
                    Yes: {
                        label: 'Yes',
                        className: 'btn green',
                        callback: function () {
                            getInvestmentPackageList(ajaxParams);
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