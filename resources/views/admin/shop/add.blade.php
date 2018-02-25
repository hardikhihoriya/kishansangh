@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>
            {{trans('adminlabels.SHOP_MANAGEMENT')}}
            <small>{{trans('adminlabels.SHOPS')}}</small>
        </h1>     
    </section>
    <section class="content">
        <div class="row">
            <!-- right column -->
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo (count($shop) > 0) ? trans('adminlabels.EDIT') : trans('adminlabels.ADD') ?> {{trans('adminlabels.SHOP')}}</h3>
                    </div>
                    <form class="form-horizontal" enctype="multipart/form-data" id="addUpdateShop" method="POST" action="{{ url('admin/shops/set') }}">
                        {{ csrf_field() }}
                        <div class="box-body">
                            <?php $id = (count($shop) > 0 && isset($shop[0])) ? $shop[0]->id : '0'; ?> 
                            <input type="hidden" name="id" value="{{$id}}">
                            <input type="hidden" name="hidden_shop_logo" value="<?php echo (count($shop) > 0 && isset($shop[0])) ? $shop[0]->shop_logo : ''; ?>">
                            
                            @if($id == '0')
                            <!-- User List -->
                            <div class="form-group">
                                <label for="user_id" class="col-md-2 control-label"> {{ trans('adminlabels.USER_LIST') }} </label>
                                <div class="col-md-6">
                                    <select class="form-control" id="user_id" name="user_id">
                                        <option value="">{{ trans('adminlabels.SELECT_USER') }}</option>
                                        @foreach ($users as $_users)
                                            <option value="{{$_users->id}}">{{$_users->first_name . (!empty($_users->middle_name) ? ' ' . $_users->middle_name : '') . (!empty($_users->last_name) ? ' ' . $_users->last_name : '')}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif

                            <!-- Shop Type -->
                            <div class="form-group">
                                <label for="shop_type_id" class="col-md-2 control-label"> {{ trans('adminlabels.SHOP_TYPE') }} </label>
                                <div class="col-md-6">
                                    <select class="form-control" id="shop_type_id" name="shop_type_id">
                                        <option value="">{{ trans('adminlabels.SELECT_SHOP_TYPE') }}</option>
                                        @foreach ($shopType as $_shopType)
                                            <option value="{{$_shopType->id}}" @if(count($shop) > 0  && isset($shop[0]) && $shop[0]->shop_type_id == $_shopType->id) selected="selected" @endif>{{$_shopType->shop_type_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Shop Package -->
                            <div class="form-group">
                                <label for="shop_package_id" class="col-md-2 control-label"> {{ trans('adminlabels.SHOP_PACKAGE') }} </label>
                                <div class="col-md-6">
                                    <select class="form-control" id="shop_package_id" name="shop_package_id">
                                        <option value="">{{ trans('adminlabels.SELECT_SHOP_PACKAGE') }}</option>
                                        @foreach ($shopPackage as $_shopPackage)
                                            <option value="{{$_shopPackage->id}}" @if(count($shop) > 0 && isset($shop[0]) && $shop[0]->shop_package_id == $_shopPackage->id) selected="selected" @endif>{{$_shopPackage->package_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Shop Name -->
                            <div class="form-group">
                                <label for="shop_name" class="col-md-2 control-label"> {{ trans('adminlabels.SHOP_NAME') }} </label>
                                <div class="col-md-6">
                                    <input id="shop_name" type="text" class="form-control" name="shop_name" value="{{ (old('shop_name') ? old('shop_name') : (count($shop) > 0 && isset($shop[0]) ? $shop[0]->shop_name : $shop->shop_name)) }}" autofocus>
                                </div>
                            </div>

                            <!-- Shop Email -->
                            <div class="form-group">
                                <label for="shop_email" class="col-md-2 control-label"> {{ trans('adminlabels.SHOP_EMAIL') }} </label>
                                <div class="col-md-6">
                                    <input id="shop_email" type="text" class="form-control" name="shop_email" value="{{ (old('shop_email') ? old('shop_email') : (count($shop) > 0 && isset($shop[0]) ? $shop[0]->shop_email : $shop->shop_email)) }}" autofocus>
                                </div>
                            </div>

                            <!-- Shop Web URL -->
                            <div class="form-group">
                                <label for="shop_web_url" class="col-md-2 control-label"> {{ trans('adminlabels.SHOP_WEB_URL') }} </label>
                                <div class="col-md-6">
                                    <input id="shop_web_url" type="text" class="form-control" name="shop_web_url" value="{{ (old('shop_web_url') ? old('shop_web_url') : (count($shop) > 0 && isset($shop[0]) ? $shop[0]->shop_web_url : $shop->shop_web_url)) }}" autofocus>
                                </div>
                            </div>
                            
                            <!-- Shop Phone Number -->
                            <div class="form-group">
                                <label for="shop_phone_no" class="col-md-2 control-label"> {{ trans('adminlabels.SHOP_PHONE_NUMBER') }} </label>
                                <div class="col-md-6">
                                    <input id="shop_phone_no" type="text" class="form-control allownumericwithoutdecimal" name="shop_phone_no" value="{{ (old('shop_phone_no') ? old('shop_phone_no') : (count($shop) > 0 && isset($shop[0]) ? $shop[0]->shop_phone_no : $shop->shop_phone_no)) }}" autofocus>
                                </div>
                            </div>

                            <!-- Shop Anniverssary Date -->
                            <div class="form-group">
                                <label for="shop_anniversary_date" class="col-md-2 control-label"> {{ trans('adminlabels.SHOP_ANNIVERSSARY_DATE') }} </label>
                                <div class="col-md-6">
                                    <input id="shop_anniversary_date" type="text" class="form-control datetimepicker" name="shop_anniversary_date" value="{{ (old('shop_anniversary_date') ? old('shop_anniversary_date') : (count($shop) > 0 && isset($shop[0]) ? $shop[0]->shop_anniversary_date : $shop->shop_anniversary_date)) }}" autofocus>
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="form-group">
                                <label for="address" class="col-md-2 control-label"> {{ trans('adminlabels.ADDRESS') }} </label>
                                <div class="col-md-6">
                                    <textarea id="address" class="form-control" name="address" autofocus>{{ (old('address') ? old('address') : (count($shop) > 0 && isset($shop[0]) ? $shop[0]->address : $shop->address)) }}</textarea>
                                </div>
                            </div>

                            <!-- Zipcode -->
                            <div class="form-group">
                                <label for="zipcode" class="col-md-2 control-label"> {{ trans('adminlabels.ZIPCODE') }} </label>
                                <div class="col-md-6">
                                    <input id="zipcode" type="text" class="form-control allownumericwithoutdecimal" name="zipcode" value="{{ (old('zipcode') ? old('zipcode') : (count($shop) > 0 && isset($shop[0]) ? $shop[0]->zipcode : $shop->zipcode)) }}" autofocus>
                                </div>
                            </div>

                            <!-- Shop Logo -->
                            <div class="form-group">
                                <label for="shop_logo" class="col-md-2 control-label"> {{ trans('adminlabels.SHOP_LOGO') }} </label>
                                <div class="col-md-6">
                                    <input type="file" id="shop_logo" name="shop_logo">   
                                    <?php if (count($shop) > 0 && isset($shop[0]) && $shop[0]->id != '0') {
                                        if (File::exists(public_path($shopIconPath . $shop[0]->shop_logo)) && $shop[0]->shop_logo != '') { ?>
                                            <img src="{{ url($shopIconPath.$shop[0]->shop_logo) }}" alt="{{$shop[0]->shop_logo}}"  height="70" width="70">
                                        <?php } else { ?>
                                            <img src="{{ asset('/images/default.png')}}" alt="Default Image" height="70" width="70">
                                        <?php }
                                    } ?>
                                </div>
                            </div>
                            
                            <!-- Shop Timing -->
                            @foreach($dayList as $day => $_dayList)
                                <?php $key = false; ?>
                                @if(isset($shop[0]->shopTime) && count($shop[0]->shopTime) > 0)
                                    <?php $key = App\Helpers\Common::in_array($_dayList['value'], $shop[0]->shopTime->toArray()) ?>
                                @endif
                                <div class="form-group">
                                    <label for="days" class="col-md-2 control-label"> {{ $_dayList['name'] }} </label>
                                    <div class="col-md-3">
                                        <input id="start_time{{$day}}" type="text" class="form-control timepicker startDate" name="start_time[]" placeholder="Start Time" value="{{ (isset($shop[0]->shopTime) && !empty($shop[0]->shopTime) && $key !== false) ? $shop[0]->shopTime[$key]->start_time : '' }}" autofocus>
                                    </div>
                                    <div class="col-md-3">
                                        <input id="close_time{{$day}}" type="text" class="form-control timepicker closeDate" name="close_time[]" placeholder="Close Time" value="{{ (isset($shop[0]->shopTime) && !empty($shop[0]->shopTime) && $key !== false) ? $shop[0]->shopTime[$key]->close_time : '' }}" autofocus>
                                    </div>
                                </div>
                            @endforeach
                            
                            <!-- Status -->
                            <div class="form-group">
                                <label for="status" class="col-md-2 control-label"> {{ trans('adminlabels.SHOP_STATUS') }} </label>
                                <div class="col-md-6">
                                    <select class="form-control" id="status" name="status">
                                        @foreach ($status as $_status)
                                            <option value="{{$_status['value']}}" @if(count($shop) > 0 && isset($shop[0]) && $shop[0]->status == $_status['value']) selected="selected" @endif >{{$_status['name']}}</option>
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
                                    <a href="{{url('admin/shops')}}" class="btn btn-primary">{{ trans('adminlabels.CANCEL') }}</a>
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
<script type="text/javascript" src="{{ asset('js/admin/moment-with-locales.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/admin/bootstrap-datetimepicker.min.js') }}"></script>
<script>
    jQuery(document).ready(function () {
        $('#shop_anniversary_date').datetimepicker({
            'format': 'YYYY-MM-DD'
        });

        $('.datetimepicker').keydown(function(e) {
            e.preventDefault();
            return false;
        });

        $(".allownumericwithoutdecimal").on("keypress keyup blur",function (event) {    
           $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
        
        $('.timepicker').datetimepicker({
            format: 'LT'
        });        

        var ID = '<?php echo $id; ?>';
        var shopIconRequired = (ID == '0' ? true : false);        
        $("#addUpdateShop").validate({
            ignore: ":hidden:not(select)",
            rules: {
                user_id: {
                    required: shopIconRequired
                },
                shop_type_id: {
                    required: true
                },
                shop_package_id: {
                    required: true
                },
                shop_name: {
                    required: true,
                    maxlength: 100
                },
                shop_email: {
                    required: true,
                    email: true,
                    maxlength: 100
                },
                shop_web_url: {
                    required: true,
                    maxlength: 255
                },
                shop_phone_no: {
                    required: true,
                    digits: true,
                    maxlength: 10
                },
                shop_anniversary_date: {
                    required: true
                },
                address: {
                    required: true,
                    maxlength: 500
                },
                zipcode: {
                    required: true,
                    digits:true,
                    maxlength:6
                },
                shop_logo: {
                    required: shopIconRequired,
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