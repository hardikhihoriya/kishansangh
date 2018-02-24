@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>
            {{trans('adminlabels.SHOP_MARKETING_MANAGEMENT')}}
            <small>{{trans('adminlabels.SHOP_MARKETINGS')}}</small>
        </h1>     
    </section>

    <section class="content">
        <div class="row">
            <!-- right column -->
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo ($shopMarketing->id) ? trans('adminlabels.EDIT') : trans('adminlabels.ADD') ?> {{trans('adminlabels.SHOP_MARKETING')}}</h3>
                    </div>
                    <form class="form-horizontal" id="addUpdateShopMarketing" method="POST" action="{{ url('admin/shop-marketing/set') }}">
                        {{ csrf_field() }}
                        <div class="box-body">
                            
                            <?php $id = ($shopMarketing->id) ? $shopMarketing->id : '0' ?>
                            <input type="hidden" name="id" value="{{$id}}">
                            
                            <!-- Shop Marketing Name -->
                            <div class="form-group">
                                <label for="shop_marketing_name" class="col-md-2 control-label"> {{ trans('adminlabels.SHOP_MARKETING_NAME') }} </label>
                                <div class="col-md-6">
                                    <input id="shop_marketing_name" type="text" class="form-control" name="shop_marketing_name" value="{{ (old('shop_marketing_name') ? old('shop_marketing_name') : $shopMarketing->shop_marketing_name) }}" autofocus>
                                </div>
                            </div>
                            
                            <!-- Shop Marketing Member -->
                            <div class="form-group">
                                <label for="member" class="col-md-2 control-label"> {{ trans('adminlabels.SHOP_MARKETING_MEMBER') }} </label>
                                <div class="col-md-6">
                                    <input id="member" type="text" class="form-control allownumericwithoutdecimal" name="member" value="{{ (old('member') ? old('member') : $shopMarketing->member) }}" autofocus>
                                </div>
                            </div>

                            <!-- Detail -->
                            <div class="form-group">
                                <label for="description" class="col-md-2 control-label"> {{ trans('adminlabels.SHOP_MARKETING_DESCRIPTION') }} </label>
                                <div class="col-md-6">
                                    <textarea id="description" class="form-control" name="description" autofocus>{{ (old('description') ? old('description') : $shopMarketing->description) }}</textarea>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="form-group">
                                <label for="status" class="col-md-2 control-label"> {{ trans('adminlabels.SHOP_MARKETING_STATUS') }} </label>
                                <div class="col-md-6">
                                    <select class="form-control" id="status" name="status">
                                        @foreach ($status as $_status)
                                            <option value="{{$_status['value']}}" @if($shopMarketing->status == $_status['value']) selected="selected" @endif >{{$_status['name']}}</option>
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
                                    <a href="{{url('admin/shop-marketing')}}" class="btn btn-primary">{{ trans('adminlabels.CANCEL') }}</a>
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
        $(".allownumericwithoutdecimal").on("keypress keyup blur",function (event) {    
           $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });        
        
        $("#addUpdateShopMarketing").validate({
            ignore: ":hidden:not(select)",
            rules: {
                shop_marketing_name: {
                    required: true,
                    maxlength: 100
                },
                member: {
                    required: true,
                    digits: true
                },
                description: {
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