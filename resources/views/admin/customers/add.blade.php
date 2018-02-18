@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>
            {{trans('adminlabels.CUSTOMER_MANAGEMENT')}}
            <small>{{trans('adminlabels.CUSTOMERS')}}</small>
        </h1>     
    </section>

    <section class="content">
        <div class="row">
            <!-- right column -->
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo ($customer->id) ? trans('adminlabels.EDIT') : trans('adminlabels.ADD') ?> {{trans('adminlabels.CUSTOMER')}}</h3>
                    </div>
                    <form class="form-horizontal" enctype="multipart/form-data" id="addUpdateCustomer" method="POST" action="{{ url('admin/customer/set') }}">
                        {{ csrf_field() }}
                        <div class="box-body">
                            <?php $id = ($customer->id) ? $customer->id : '0' ?>
                            <input type="hidden" name="id" value="{{$id}}">
                            <input type="hidden" name="hidden_customer_pic" value="<?php echo ($customer->id) ? ($customer->nominee_photo != null ? $customer->nominee_photo : '') : ''; ?>">
                            <input type="hidden" name="hidden_proof_front_pic" value="<?php echo ($customer->id) ? ($customer->nominee_id_proof_front ? $customer->nominee_id_proof_front : '') : ''; ?>">
                            <input type="hidden" name="hidden_proof_back_pic" value="<?php echo ($customer->id) ? ($customer->nominee_id_proof_back ? $customer->nominee_id_proof_back : '') : ''; ?>">
                            
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
                            
                            <!-- First Name -->
                            <div class="form-group">
                                <label for="nominee_name" class="col-md-2 control-label"> {{ trans('adminlabels.NOMINEE_NAME') }} </label>
                                <div class="col-md-6">
                                    <input id="nominee_name" type="text" class="form-control" name="nominee_name" value="{{ (old('nominee_name') ? old('nominee_name') : $customer->nominee_name) }}" autofocus>
                                </div>
                            </div>
                            
                            <!-- Address -->
                            <div class="form-group">
                                <label for="nominee_address" class="col-md-2 control-label"> {{ trans('adminlabels.ADDRESS') }} </label>
                                <div class="col-md-6">
                                    <textarea id="nominee_address" class="form-control" name="nominee_address" autofocus>{{ (old('nominee_address') ? old('nominee_address') : $customer->nominee_address) }}</textarea>
                                </div>
                            </div>

                            <!-- Nominee Image -->
                            <div class="form-group">
                                <label for="nominee_photo" class="col-md-2 control-label"> {{ trans('adminlabels.NOMINEE_PHOTO') }} </label>
                                <div class="col-md-6">
                                    <input type="file" id="nominee_photo" name="nominee_photo">   
                                    <?php if (isset($customer->id) && $customer->id != '0') {
                                        if (File::exists(public_path($customerPicPath . $customer->nominee_photo)) && $customer->nominee_photo != '') { ?>
                                            <img src="{{ url($customerPicPath.$customer->nominee_photo) }}" alt="{{$customer->nominee_photo}}"  height="70" width="70">
                                        <?php } else { ?>
                                            <img src="{{ asset('/images/default.png')}}" alt="Default Image" height="70" width="70">
                                        <?php }
                                    } ?>
                                </div>
                            </div>

                            <!-- Nominee Proof front -->
                            <div class="form-group">
                                <label for="nominee_id_proof_front" class="col-md-2 control-label"> {{ trans('adminlabels.NOMINEE_ID_PROOF_FRONT') }} </label>
                                <div class="col-md-6">
                                    <input type="file" id="nominee_id_proof_front" name="nominee_id_proof_front">   
                                    <?php if (isset($customer->id) && $customer->id != '0') {
                                        if (File::exists(public_path($customerProofFrontPath . $customer->nominee_id_proof_front)) && $customer->nominee_id_proof_front != '') { ?>
                                            <img src="{{ url($customerProofFrontPath.$customer->nominee_id_proof_front) }}" alt="{{$customer->nominee_id_proof_front}}"  height="70" width="70">
                                        <?php } else { ?>
                                            <img src="{{ asset('/images/default.png')}}" alt="Default Image" height="70" width="70">
                                        <?php }
                                    } ?>
                                </div>
                            </div>
                            
                            <!-- Nominee Proof Back -->
                            <div class="form-group">
                                <label for="nominee_id_proof_back" class="col-md-2 control-label"> {{ trans('adminlabels.NOMINEE_ID_PROOF_BACK') }} </label>
                                <div class="col-md-6">
                                    <input type="file" id="nominee_id_proof_back" name="nominee_id_proof_back">   
                                    <?php if (isset($customer->id) && $customer->id != '0') {
                                        if (File::exists(public_path($customerProofBackPath . $customer->nominee_id_proof_back)) && $customer->nominee_id_proof_back != '') { ?>
                                            <img src="{{ url($customerProofBackPath.$customer->nominee_id_proof_back) }}" alt="{{$customer->nominee_id_proof_back}}"  height="70" width="70">
                                        <?php } else { ?>
                                            <img src="{{ asset('/images/default.png')}}" alt="Default Image" height="70" width="70">
                                        <?php }
                                    } ?>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <div class="form-group">
                                <div class="col-md-1 col-md-offset-2">
                                    <button type="submit" class="btn btn-primary">{{ trans('adminlabels.SUBMIT') }}</button>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{url('admin/customer')}}" class="btn btn-primary">{{ trans('adminlabels.CANCEL') }}</a>
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
        var nomineePhoto = '<?php echo ($customer->id) ? ($customer->nominee_photo != null ? $customer->nominee_photo : '') : ''; ?>';
        var nomineeIDProofFront = '<?php echo ($customer->id) ? ($customer->nominee_photo != null ? $customer->nominee_photo : '') : ''; ?>';
        var nomineePhotoRequired = (nomineePhoto == '' ? true : false);
        var nomineeIDProofFrontRequired = (nomineeIDProofFront == '' ? true : false);
        $("#addUpdateCustomer").validate({
            ignore: ":hidden:not(select)",
            rules: {
                nominee_name: {
                    required: true,
                    maxlength: 100
                },
                nominee_address: {
                    required: true,
                    maxlength: 100
                },
                nominee_photo: {
                    required: nomineePhotoRequired,
                    extension: "png|jpeg|jpg|bmp"
                },
                nominee_id_proof_front: {
                    required: nomineeIDProofFrontRequired,
                    extension: "png|jpeg|jpg|bmp"
                }
            }
        });
    });
</script>
@endsection