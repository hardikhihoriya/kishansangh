@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>
            {{trans('adminlabels.INVESTMENT_PACKAGE_MANAGEMENT')}}
            <small>{{trans('adminlabels.INVESTMENT_PACKAGES')}}</small>
        </h1>     
    </section>

    <section class="content">
        <div class="row">
            <!-- right column -->
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo ($investmentPackage->id) ? trans('adminlabels.EDIT') : trans('adminlabels.ADD') ?> {{trans('adminlabels.INVESTMENT_PACKAGE')}}</h3>
                    </div>
                    <form class="form-horizontal" id="addUpdateInvestmentPackage" method="POST" action="{{ url('admin/investment-package/set') }}">
                        {{ csrf_field() }}
                        <div class="box-body">
                            
                            <?php $id = ($investmentPackage->id) ? $investmentPackage->id : '0' ?>
                            <input type="hidden" name="id" value="{{$id}}">
                            
                            <!-- Investment Package Name -->
                            <div class="form-group">
                                <label for="investment_package_name" class="col-md-2 control-label"> {{ trans('adminlabels.INVESTMENT_PACKAGE_NAME') }} </label>
                                <div class="col-md-6">
                                    <input id="investment_package_name" type="text" class="form-control" name="investment_package_name" value="{{ (old('investment_package_name') ? old('investment_package_name') : $investmentPackage->investment_package_name) }}" autofocus>
                                </div>
                            </div>

                            <!-- Investment Package Year -->
                            <div class="form-group">
                                <label for="investment_package_year" class="col-md-2 control-label"> {{ trans('adminlabels.INVESTMENT_PACKAGE_YEAR') }} </label>
                                <div class="col-md-6">
                                    <input id="investment_package_year" type="text" class="form-control" name="investment_package_year" value="{{ (old('investment_package_year') ? old('investment_package_year') : $investmentPackage->investment_package_year) }}" autofocus>
                                </div>
                            </div>

                            <!-- Investment Package Size -->
                            <div class="form-group">
                                <label for="investment_package_size" class="col-md-2 control-label"> {{ trans('adminlabels.INVESTMENT_PACKAGE_SIZE') }} </label>
                                <div class="col-md-6">
                                    <input id="investment_package_size" type="text" class="form-control allownumericwithoutdecimal" name="investment_package_size" value="{{ (old('investment_package_size') ? old('investment_package_size') : $investmentPackage->investment_package_size) }}" autofocus>
                                </div>
                            </div>

                            <!-- Investment Package Duration (In year) -->
                            <div class="form-group">
                                <label for="investment_package_duration" class="col-md-2 control-label"> {{ trans('adminlabels.INVESTMENT_PACKAGE_DURATION') }} </label>
                                <div class="col-md-6">
                                    <input id="investment_package_duration" type="text" class="form-control allownumericwithoutdecimal" name="investment_package_duration" value="{{ (old('investment_package_duration') ? old('investment_package_duration') : $investmentPackage->investment_package_duration) }}" autofocus>
                                </div>
                            </div>

                            <!-- Investment Package description -->
                            <div class="form-group">
                                <label for="investment_package_description" class="col-md-2 control-label"> {{ trans('adminlabels.INVESTMENT_PACKAGE_DESCRIPTION') }} </label>
                                <div class="col-md-6">
                                    <textarea id="investment_package_description" class="form-control" name="investment_package_description" autofocus>{{ (old('investment_package_description') ? old('investment_package_description') : $investmentPackage->investment_package_description) }}</textarea>
                                </div>
                            </div>

                            <!-- Investment Package Price -->
                            <div class="form-group">
                                <label for="investment_package_price" class="col-md-2 control-label"> {{ trans('adminlabels.INVESTMENT_PACKAGE_PRICE') }} </label>
                                <div class="col-md-6">
                                    <input id="investment_package_price" type="text" class="form-control allownumericwithdecimal" name="investment_package_price" value="{{ (old('investment_package_price') ? old('investment_package_price') : $investmentPackage->investment_package_price) }}" autofocus>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="form-group">
                                <label for="status" class="col-md-2 control-label"> {{ trans('adminlabels.INVESTMENT_PACKAGE_STATUS') }} </label>
                                <div class="col-md-6">
                                    <select class="form-control" id="status" name="status">
                                        @foreach ($status as $_status)
                                            <option value="{{$_status['value']}}" @if($investmentPackage->status == $_status['value']) selected="selected" @endif >{{$_status['name']}}</option>
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
                                    <a href="{{url('admin/investment-package')}}" class="btn btn-primary">{{ trans('adminlabels.CANCEL') }}</a>
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

        $(".allownumericwithdecimal").on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
            $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
        
        $("#addUpdateInvestmentPackage").validate({
            ignore: ":hidden:not(select)",
            rules: {
                investment_package_name: {
                    required: true,
                    maxlength: 100
                },
                investment_package_year: {
                    required: true,
                    digits: true,
                    maxlength: 4
                },
                investment_package_size: {
                    required: true,
                    digits: true,
                    max: 25
                },
                investment_package_duration: {
                    required: true,
                    digits: true,
                    max: 25
                },
                investment_package_description: {
                    required: true,
                    maxlength: 500
                },
                investment_package_price: {
                    required: true,
                    number: true
                },
                status: {
                    required: true
                }
            }
        });
    });
</script>
@endsection