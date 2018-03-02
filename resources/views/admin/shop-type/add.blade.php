@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>
            {{trans('adminlabels.SHOP_TYPE_MANAGEMENT')}}
            <small>{{trans('adminlabels.SHOP_TYPES')}}</small>
        </h1>     
    </section>

    <section class="content">
        <div class="row">
            <!-- right column -->
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo ($shopType->id) ? trans('adminlabels.EDIT') : trans('adminlabels.ADD') ?> {{trans('adminlabels.SHOP_TYPE')}}</h3>
                    </div>
                    <form class="form-horizontal" enctype="multipart/form-data" id="addUpdateShopType" method="POST" action="{{ url('admin/shop-type/set') }}">
                        {{ csrf_field() }}
                        <div class="box-body">
                            
                            <?php $id = ($shopType->id) ? $shopType->id : '0' ?>
                            <input type="hidden" name="id" value="{{$id}}">
                            <input type="hidden" name="hidden_shop_type_icon" value="<?php echo ($shopType->id) ? $shopType->shop_type_icon : ''; ?>">
                            
                            <!-- Shop Type -->
                            <div class="form-group">
                                <label for="shop_type_name" class="col-md-2 control-label"> {{ trans('adminlabels.SHOP_TYPE') }} </label>
                                <div class="col-md-6">
                                    <input id="shop_type_name" type="text" class="form-control" name="shop_type_name" value="{{ (old('shop_type_name') ? old('shop_type_name') : $shopType->shop_type_name) }}" autofocus>
                                </div>
                            </div>
                            
                            <!-- Detail -->
                            <div class="form-group">
                                <label for="shop_type_detail" class="col-md-2 control-label"> {{ trans('adminlabels.SHOP_TYPE_DETAIL') }} </label>
                                <div class="col-md-6">
                                    <textarea id="shop_type_detail" class="form-control" name="shop_type_detail" autofocus>{{ (old('shop_type_detail') ? old('shop_type_detail') : $shopType->shop_type_detail) }}</textarea>
                                </div>
                            </div>

                            <!-- Shop Type Icon -->
                            <div class="form-group">
                                <label for="shop_type_icon" class="col-md-2 control-label"> {{ trans('adminlabels.SHOP_TYPE_ICON') }} </label>
                                <div class="col-md-6">
                                    <input type="file" id="shop_type_icon" name="shop_type_icon">   
                                    <?php if (isset($shopType->id) && $shopType->id != '0') {
                                        if (File::exists(public_path($shopTypeIconPath . $shopType->shop_type_icon)) && $shopType->shop_type_icon != '') { ?>
                                            <img src="{{ url($shopTypeIconPath.$shopType->shop_type_icon) }}" alt="{{$shopType->shop_type_icon}}"  height="70" width="70">
                                        <?php } else { ?>
                                            <img src="{{ asset('/images/default.png')}}" alt="Default Image" height="70" width="70">
                                        <?php }
                                    } ?>
                                </div>
                            </div>
                            
                            <!-- Status -->
                            <div class="form-group">
                                <label for="status" class="col-md-2 control-label"> {{ trans('adminlabels.SHOP_TYPE_STATUS') }} </label>
                                <div class="col-md-6">
                                    <select class="form-control" id="status" name="status">
                                        @foreach ($status as $_status)
                                            <option value="{{$_status['value']}}" @if($shopType->status == $_status['value']) selected="selected" @endif >{{$_status['name']}}</option>
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
                                    <a href="{{url('admin/shop-type')}}" class="btn btn-primary">{{ trans('adminlabels.CANCEL') }}</a>
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
        var ID = '<?php echo $id; ?>';
        var shopTypeIconRequired = (ID == '0' ? true : false);        
        $("#addUpdateShopType").validate({
            ignore: ":hidden:not(select)",
            rules: {
                shop_type_name: {
                    required: true,
                    maxlength: 100
                },
                shop_type_detail: {
                    required: true,
                    maxlength: 500
                },
                shop_type_icon: {
                    required: shopTypeIconRequired,
                    extension: "png|jpeg|jpg|bmp"
                },
                status: {
                    required: true
                }
            }
        });
    });
</script>
@endsection