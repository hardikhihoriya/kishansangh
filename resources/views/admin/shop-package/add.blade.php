@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>
            {{trans('adminlabels.SHOP_PACKAGE_MANAGEMENT')}}
            <small>{{trans('adminlabels.SHOP_PACKAGES')}}</small>
        </h1>     
    </section>

    <section class="content">
        <div class="row">
            <!-- right column -->
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo ($shopPackage->id) ? trans('adminlabels.EDIT') : trans('adminlabels.ADD') ?> {{trans('adminlabels.SHOP_PACKAGE')}}</h3>
                    </div>
                    <form class="form-horizontal" id="addUpdateShopPackage" method="POST" action="{{ url('admin/shop-package/set') }}">
                        {{ csrf_field() }}
                        <div class="box-body">
                            
                            <?php $id = ($shopPackage->id) ? $shopPackage->id : '0' ?>
                            <input type="hidden" name="id" value="{{$id}}">
                            
                            <!-- Shop Marketing Name -->
                            <div class="form-group">
                                <label for="shop_marketing_id" class="col-md-2 control-label"> {{ trans('adminlabels.SHOP_PACKAGE_MARKETING_NAME') }} </label>
                                <div class="col-md-6">
                                    <select class="form-control" id="shop_marketing_id" name="shop_marketing_id">
                                        <option value="">{{ trans('adminlabels.SELECT_SHOP_MARKETING') }}</option>
                                        @foreach ($shopMarketingList as $_shopMarketingList)
                                            <option value="{{$_shopMarketingList->id}}" @if($_shopMarketingList->id == $shopPackage->shop_marketing_id) selected=selected @endif >{{$_shopMarketingList->shop_marketing_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Shop Package Name -->
                            <div class="form-group">
                                <label for="package_name" class="col-md-2 control-label"> {{ trans('adminlabels.SHOP_PACKAGE_NAME') }} </label>
                                <div class="col-md-6">
                                    <input id="package_name" type="text" class="form-control" name="package_name" value="{{ (old('package_name') ? old('package_name') : $shopPackage->package_name) }}" autofocus>
                                </div>
                            </div>

                            <!-- Boosting Point -->
                            <div class="form-group">
                                <label for="boosting_point" class="col-md-2 control-label"> {{ trans('adminlabels.SHOP_PACKAGE_BOOSTING_POINT') }} </label>
                                <div class="col-md-6">
                                    <input id="boosting_point" type="number" class="form-control allownumericwithdecimal" name="boosting_point" value="{{ (old('boosting_point') ? old('boosting_point') : $shopPackage->boosting_point) }}" autofocus>
                                </div>
                            </div>

                            <!-- Per Day SMS -->
                            <div class="form-group">
                                <label for="per_day_SMS" class="col-md-2 control-label"> {{ trans('adminlabels.SHOP_PACKAGE_PER_DAY_SMS') }} </label>
                                <div class="col-md-6">
                                    <input id="per_day_SMS" type="text" class="form-control allownumericwithoutdecimal" name="per_day_SMS" value="{{ (old('per_day_SMS') ? old('per_day_SMS') : $shopPackage->per_day_SMS) }}" autofocus>
                                </div>
                            </div>

                            <!-- Price -->
                            <div class="form-group">
                                <label for="price" class="col-md-2 control-label"> {{ trans('adminlabels.SHOP_PACKAGE_PRICE') }} </label>
                                <div class="col-md-6">
                                    <input id="price" type="text" class="form-control allownumericwithdecimal" name="price" value="{{ (old('price') ? old('price') : $shopPackage->price) }}" autofocus>
                                </div>
                            </div>

                            <!-- Package_description -->
                            <div class="form-group">
                                <label for="package_description" class="col-md-2 control-label"> {{ trans('adminlabels.SHOP_PACKAGE_DESCRIPTION') }} </label>
                                <div class="col-md-6">
                                    <textarea id="package_description" class="form-control" name="package_description" autofocus>{{ (old('package_description') ? old('package_description') : $shopPackage->package_description) }}</textarea>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="form-group">
                                <label for="status" class="col-md-2 control-label"> {{ trans('adminlabels.SHOP_PACKAGE_STATUS') }} </label>
                                <div class="col-md-6">
                                    <select class="form-control" id="status" name="status">
                                        @foreach ($status as $_status)
                                            <option value="{{$_status['value']}}" @if($shopPackage->status == $_status['value']) selected="selected" @endif >{{$_status['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                        </div>
                        <div class="box-footer">
                            <div class="form-group">
                                <div class="col-md-1 col-md-offset-2">
                                    <button type="submit" class="btn btn-primary">{{ trans('adminlabels.SUBMIT') }}</button>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{url('admin/shop-package')}}" class="btn btn-primary">{{ trans('adminlabels.CANCEL') }}</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.box -->
            </div>
            <!--/.col (right) -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
    <!-- /.content-wrapper -->
@endsection

@section('script')
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/additional-methods.js"></script>
<script>
    jQuery(document).ready(function () {
        $(".allownumericwithdecimal").on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
            $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
        
        $(".allownumericwithoutdecimal").on("keypress keyup blur",function (event) {    
           $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });        
        
        $("#addUpdateShopPackage").validate({
            ignore: ":hidden:not(select)",
            rules: {
                shop_marketing_id: {
                    required: true
                },
                package_name: {
                    required: true,
                    maxlength: 100
                },
                boosting_point: {
                    required: true,
                    number: true
                },
                per_day_SMS: {
                    required: true,
                    digits:true
                },
                price: {
                    required: true,
                    number: true
                },
                package_description: {
                    required: true,
                    maxlength: 500
                },
                status: {
                    required: true
                }
            }
        });
    });
</script>
@endsection