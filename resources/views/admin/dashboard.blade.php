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
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <h3>
                <a href='admin/sanghusers' >
                    {{ $totalRegistration }} {{trans('adminlabels.TOTAL_REGISTERATION')}}
                </a>
            </h3>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <h3>
                <a href='admin/customer' >
                    {{ $customer }} {{trans('adminlabels.CUSTOMER')}}
                </a>
            </h3>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <h3>
                <a href='admin/sanghusers' >
                    {{ $vendor }} {{trans('adminlabels.VENDOR')}}
                </a>
            </h3>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            
        </div>
    </div>
</section>
@stop