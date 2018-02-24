@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>
            {{trans('adminlabels.USER_MANAGEMENT')}}
            <small>{{trans('adminlabels.USERS')}}</small>
        </h1>     
    </section>

    <section class="content">
        <div class="row">
            <!-- right column -->
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo ($user->id) ? trans('adminlabels.EDIT') : trans('adminlabels.REGISTER') ?> {{trans('adminlabels.USER')}}</h3>
                    </div>
                    <form class="form-horizontal" enctype="multipart/form-data" id="registerUser" method="POST" action="{{ url('admin/sanghusers/set') }}">
                        {{ csrf_field() }}
                        <div class="box-body">
                            
                            <?php $id = ($user->id) ? $user->id : '0' ?>
                            <input type="hidden" name="id" value="{{$id}}">
                            <input type="hidden" name="hidden_user_pic" value="<?php echo ($user->id) ? $user->user_pic : ''; ?>">
                            <input type="hidden" name="hidden_user_sign" value="<?php echo ($user->id) ? $user->signature : ''; ?>">
                            <!-- First Name -->
                            <div class="form-group">
                                <label for="first_name" class="col-md-2 control-label"> {{ trans('adminlabels.FIRST_NAME') }} </label>
                                <div class="col-md-6">
                                    <input id="first_name" type="text" class="form-control" name="first_name" value="{{ (old('first_name') ? old('first_name') : $user->first_name) }}" autofocus>
                                </div>
                            </div>
                            
                            <!-- Middle Name -->
                            <div class="form-group">
                                <label for="middle_name" class="col-md-2 control-label"> {{ trans('adminlabels.MIDDLE_NAME') }} </label>
                                <div class="col-md-6">
                                    <input id="middle_name" type="text" class="form-control" name="middle_name" value="{{ (old('middle_name') ? old('middle_name') : $user->middle_name) }}" autofocus>
                                </div>
                            </div>

                            <!-- Last Name -->
                            <div class="form-group">
                                <label for="last_name" class="col-md-2 control-label"> {{ trans('adminlabels.LAST_NAME') }} </label>
                                <div class="col-md-6">
                                    <input id="last_name" type="text" class="form-control" name="last_name" value="{{ (old('last_name') ? old('last_name') : $user->last_name) }}" autofocus>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="form-group">
                                <label for="email" class="col-md-2 control-label"> {{ trans('adminlabels.EMAIL') }} </label>
                                <div class="col-md-6">
                                    <input id="email" type="text" class="form-control" name="email" value="{{ (old('email') ? old('email') : $user->email) }}" autofocus>
                                </div>
                            </div>

                            <!-- Phone Number -->
                            <div class="form-group">
                                <label for="phone_no" class="col-md-2 control-label"> {{ trans('adminlabels.PHONE_NO') }} </label>
                                <div class="col-md-6">
                                    <input id="phone_no" type="text" class="form-control" name="phone_no" value="{{ (old('phone_no') ? old('phone_no') : $user->phone_no) }}" autofocus>
                                </div>
                            </div>

                            <!-- Birth Date -->
                            <div class="form-group">
                                <label for="birth_date" class="col-md-2 control-label"> {{ trans('adminlabels.BIRTH_DATE') }} </label>
                                <div class="col-md-6">
                                    <input id="birth_date" type="text" class="form-control datetimepicker" name="birth_date" value="{{ (old('birth_date') ? old('birth_date') : $user->birth_date) }}" autofocus>
                                </div>
                            </div>
                            
                            <!-- Gender -->
                            <?php $gender = (old('gender') ? old('gender') : (($user->id) ? $user->gender : 'male')); ?>
                            <div class="form-group">
                                <label for="gender" class="col-md-2 control-label"> {{ trans('adminlabels.GENDER') }} </label>

                                <div class="col-md-6">
                                    <input type="radio" name="gender" value='male' <?php if ($gender == 'male') { ?> checked <?php } ?>> {{ trans('adminlabels.MALE') }}
                                    <input type="radio" name="gender" value='female' <?php if ($gender == 'female') { ?> checked <?php } ?>> {{ trans('adminlabels.FEMALE') }}
                                    <input type="radio" name="gender" value='non-binary' <?php if ($gender == 'non-binary') { ?> checked <?php } ?>> {{ trans('adminlabels.NON-BINARY') }} 
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="form-group">
                                <label for="address" class="col-md-2 control-label"> {{ trans('adminlabels.ADDRESS') }} </label>
                                <div class="col-md-6">
                                    <textarea id="address" class="form-control" name="address" autofocus>{{ (old('address') ? old('address') : $user->address) }}</textarea>
                                </div>
                            </div>

                            <!-- Zipcode -->
                            <div class="form-group">
                                <label for="zipcode" class="col-md-2 control-label"> {{ trans('adminlabels.ZIPCODE') }} </label>
                                <div class="col-md-6">
                                    <input id="zipcode" type="text" class="form-control allownumericwithoutdecimal" name="zipcode" value="{{ (old('zipcode') ? old('zipcode') : $user->zipcode) }}" autofocus>
                                </div>
                            </div>

                            <!-- Marriage Status -->
                            <?php $married = (old('married') ? old('married') : (($user->id) ? $user->married : 'no')); ?>
                            <div class="form-group">
                                <label for="married" class="col-md-2 control-label"> {{ trans('adminlabels.MARRIED') }} </label>

                                <div class="col-md-6">
                                    <input type="radio" name="married" value='yes' <?php if ($married == 'yes') { ?> checked <?php } ?>> {{ trans('adminlabels.YES') }}
                                    <input type="radio" name="married" value='no' <?php if ($married == 'no') { ?> checked <?php } ?>> {{ trans('adminlabels.NO') }}
                                </div>
                            </div>
                            
                            <!-- Anniversary Date -->
                            <div id="anniversaryDate" class="form-group">
                                <?php if($user->id && $married == 'yes') { ?>
                                <label for="marriage_anniversary_date" class="col-md-2 control-label"> {{ trans('adminlabels.ANNIVERSARY_DATE') }} </label>
                                <div class="col-md-6">
                                    <input type='text' class='form-control datetimepicker' id='marriage_anniversary_date' name='marriage_anniversary_date' value='{{ (old('marriage_anniversary_date') ? old('marriage_anniversary_date') : $user->marriage_anniversary_date) }}'>
                                </div>
                                <?php } ?>
                            </div>
                            
                            <!-- User Image -->
                            <div class="form-group">
                                <label for="user_pic" class="col-md-2 control-label"> {{ trans('adminlabels.PHOTO') }} </label>
                                <div class="col-md-6">
                                    <input type="file" id="user_pic" name="user_pic">   
                                    <?php if (isset($user->id) && $user->id != '0') {
                                        if (File::exists(public_path($userPicPath . $user->user_pic)) && $user->user_pic != '') { ?>
                                            <img src="{{ url($userPicPath.$user->user_pic) }}" alt="{{$user->user_pic}}"  height="70" width="70">
                                        <?php } else { ?>
                                            <img src="{{ asset('/images/default.png')}}" alt="Default Image" height="70" width="70">
                                        <?php }
                                    } ?>
                                </div>
                            </div>

                            <!-- User Signature -->
                            <div class="form-group">
                                <label for="signature" class="col-md-2 control-label"> {{ trans('adminlabels.SIGNATURE') }} </label>
                                <div class="col-md-6">
                                    <input type="file" id="signature" name="signature">   
                                    <?php if (isset($user->id) && $user->id != '0') {
                                        if (File::exists(public_path($userSignaturePath . $user->signature)) && $user->signature != '') { ?>
                                            <img src="{{ url($userSignaturePath.$user->signature) }}" alt="{{$user->signature}}"  height="70" width="70">
                                        <?php } else { ?>
                                            <img src="{{ asset('/images/default.png')}}" alt="Default Image" height="70" width="70">
                                        <?php }
                                    } ?>
                                </div>
                            </div>
                            
                            <!-- Bank Name -->
                            <div class="form-group">
                                <label for="bank_name" class="col-md-2 control-label"> {{ trans('adminlabels.BANK_NAME') }} </label>
                                <div class="col-md-6">
                                    <input id="bank_name" type="text" class="form-control" name="bank_name" value="{{ (old('bank_name') ? old('bank_name') : $user->bank_name) }}" autofocus>
                                </div>
                            </div>

                            <!-- Account No -->
                            <div class="form-group">
                                <label for="account_no" class="col-md-2 control-label"> {{ trans('adminlabels.ACCOUNT_NO') }} </label>
                                <div class="col-md-6">
                                    <input id="account_no" type="text" class="form-control allownumericwithoutdecimal" name="account_no" value="{{ (old('account_no') ? old('account_no') : $user->account_no) }}" autofocus>
                                </div>
                            </div>

                            <!-- IFSC Code -->
                            <div class="form-group">
                                <label for="ifsc_code" class="col-md-2 control-label"> {{ trans('adminlabels.IFSC_CODE') }} </label>
                                <div class="col-md-6">
                                    <input id="ifsc_code" type="text" class="form-control" name="ifsc_code" value="{{ (old('ifsc_code') ? old('ifsc_code') : $user->ifsc_code) }}" autofocus>
                                </div>
                            </div>
                            
                            <!-- Role -->
                            <?php if(!$user->id) { ?>
                            <div class="form-group">
                                <label for="roles" class="col-md-2 control-label"> {{ trans('adminlabels.ASSIGN_ROLES') }} </label>
                                <div class="col-md-6">
                                    <select class="form-control chosen" id="roles" name="roles[]" multiple="multiple">
                                        <?php foreach ($roles as $_roles) { ?>
                                            <option value="{{$_roles->id}}"  >{{$_roles->name}}</option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <?php } ?>
                            
                        </div>
                        <div class="box-footer">
                            <div class="form-group">
                                <div class="col-md-1 col-md-offset-2">
                                    <button type="submit" class="btn btn-primary">{{ trans('adminlabels.SUBMIT') }}</button>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{url('admin/sanghusers')}}" class="btn btn-primary">{{ trans('adminlabels.CANCEL') }}</a>
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
        $('#birth_date,#marriage_anniversary_date').datetimepicker({
            'format': 'YYYY-MM-DD'
        });
        
        $('#birth_date').data("DateTimePicker").maxDate(new Date());
        
        $('#phone_no,#zipcode').on('keyup', function(){
             this.value = this.value.replace(/[^0-9\.]/g, '');
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
        
        // Add Anniversary Date Row
        $('input[type=radio][name=married]').change(function() {
            $("#anniversaryDate").html('');
            if (this.value == 'yes') {
                var fieldHTML = "<div class='form-group'>" +
                    "<label for='marriage_anniversary_date' class='col-md-2 control-label'>{{trans('adminlabels.ANNIVERSARY_DATE')}}</label>" +
                    "<div class='col-sm-6'>" +
                    "<input type='text' class='form-control datetimepicker' id='marriage_anniversary_date' name='marriage_anniversary_date' value=''>" +
                    "</div>" +
                    "</div>";
                $("#anniversaryDate").append(fieldHTML);
                $('#marriage_anniversary_date').datetimepicker({
                    'format': 'YYYY-MM-DD'
                });
                $('.datetimepicker').keydown(function(e) {
                    e.preventDefault();
                    return false;
                });
                $('input[name="marriage_anniversary_date"]').rules("add", {// <- apply rule to new field
                    required: true
                });
            }
        });

        $("#registerUser").validate({
            ignore: ":hidden:not(select)",
            rules: {
                first_name: {
                    required: true,
                    maxlength: 100
                },
                middle_name: {
                    required: true,
                    maxlength: 100
                },
                last_name: {
                    required: true,
                    maxlength: 100
                },
                email: {
                    required: true,
                    email: true,
                    maxlength: 100
                },
                phone_no: {
                    required: true,
                    digits: true,
                    maxlength: 10
                },
                birth_date: {
                    required: true
                },
                gender: {
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
                married: {
                    required: true
                },
                user_pic: {
                    extension: "png|jpeg|jpg|bmp"
                },
                signature: {
                    extension: "png|jpeg|jpg|bmp"
                },
                bank_name: {
                    required: true,
                    maxlength: 100
                },
                account_no: {
                    required: true,
                    digits: true,
                    maxlength: 20
                },
                ifsc_code: {
                    required: true,
                    maxlength: 15
                }
            }
        });
        
    });
</script>
@endsection