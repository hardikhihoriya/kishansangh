@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>
            {{trans('adminlabels.VENDOR_MANAGEMENT')}}
            <small>{{trans('adminlabels.VENDORS')}}</small>
        </h1>     
    </section>

    <section class="content">
        <div class="row">
            <!-- right column -->
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo ($vendor->id) ? trans('adminlabels.EDIT') : trans('adminlabels.ADD') ?> {{trans('adminlabels.VENDOR')}}</h3>
                    </div>
                    <form class="form-horizontal" id="addUpdateVendor" method="POST" action="{{ url('admin/vendor/set') }}">
                        {{ csrf_field() }}
                        <div class="box-body">
                            <?php $id = ($vendor->id) ? $vendor->id : '0' ?>
                            <input type="hidden" name="id" value="{{$id}}">
                            
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
                                <label for="vendor_name" class="col-md-2 control-label"> {{ trans('adminlabels.VENDOR_NAME') }} </label>
                                <div class="col-md-6">
                                    <input id="vendor_name" type="text" class="form-control" name="vendor_name" value="{{ (old('vendor_name') ? old('vendor_name') : $vendor->vendor_name) }}" autofocus>
                                </div>
                            </div>
                            
                            <!-- Address -->
                            <div class="form-group">
                                <label for="vendor_address" class="col-md-2 control-label"> {{ trans('adminlabels.ADDRESS') }} </label>
                                <div class="col-md-6">
                                    <textarea id="vendor_address" class="form-control" name="vendor_address" autofocus>{{ (old('vendor_address') ? old('vendor_address') : $vendor->vendor_address) }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <div class="form-group">
                                <div class="col-md-1 col-md-offset-2">
                                    <button type="submit" class="btn btn-primary">{{ trans('adminlabels.SUBMIT') }}</button>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{url('admin/vendor')}}" class="btn btn-primary">{{ trans('adminlabels.CANCEL') }}</a>
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
        var ID = '<?php echo $id; ?>';
        var userIDRequired = (ID == '0' ? true : false);        
        
        $("#addUpdateVendor").validate({
            ignore: ":hidden:not(select)",
            rules: {
                user_id: {
                    required: userIDRequired
                },
                vendor_name: {
                    required: true,
                    maxlength: 100
                },
                vendor_address: {
                    required: true,
                    maxlength: 500
                }
            }
        });
    });
</script>
@endsection