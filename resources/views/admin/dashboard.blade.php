@extends('layouts.admin-master')

@section('content')
<!-- content   -->

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{trans('adminlabels.ADMIN_DASHBOARD')}}
    </h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-lg-4 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner" style="height: 93px;">
                    <div>
                        <h2 style="padding: 5px;">{{trans('adminlabels.TOTAL_REGISTERATION')}} : {{ $totalRegistration }} </h2> 
                    </div>
                </div>
                <a href='admin/sanghusers' class="small-box-footer" style="padding: 5px; font-size: 20px;">{{trans('adminlabels.TOTAL_REGISTERATION')}}<i class="fa fa-users" style="padding-left: 10px;"></i></a>
            </div>
        </div>
        <div class="col-lg-4 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-primary">
                <div class="inner" style="height: 93px;">
                    <div>
                        <h2 style="padding: 5px;">{{trans('adminlabels.CUSTOMER')}} : {{ $customer }}</h2> 
                    </div>
                </div>
                <a  href='admin/customer' class="small-box-footer" style="padding: 5px; font-size: 20px;">{{trans('adminlabels.CUSTOMER')}} <i class="fa fa-user" style="padding-left: 10px;"></i></a>
            </div>
        </div>

        <div class="col-lg-4 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-teal">
                <div class="inner" style="height: 93px;">
                    <div>
                        <h2 style="padding: 5px;">{{trans('adminlabels.VENDOR')}} : {{ $vendor }} </h2> 
                    </div>
                </div>
                <a href='admin/vendor' class="small-box-footer" style="padding: 5px; font-size: 20px;">{{trans('adminlabels.VENDOR')}} <i class="fa fa-shopping-cart" style="padding-left: 10px;"></i></a>
            </div>
        </div>         
    </div>
</section>
@stop