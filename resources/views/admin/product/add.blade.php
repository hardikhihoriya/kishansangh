@extends('layouts.admin-master')

@section('content')
    <section class="content-header">
        <h1>
            {{trans('adminlabels.PRODUCT_MANAGEMENT')}}
            <small>{{trans('adminlabels.PRODUCTS')}}</small>
        </h1>     
    </section>

    <section class="content">
        <div class="row">
            <!-- right column -->
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo ($product->id) ? trans('adminlabels.EDIT') : trans('adminlabels.ADD') ?> {{trans('adminlabels.PRODUCT')}}</h3>
                    </div>
                    <form class="form-horizontal" id="addUpdateProduct" method="POST" action="{{ url('admin/product/set') }}">
                        {{ csrf_field() }}
                        <div class="box-body">
                            
                            <?php $id = ($product->id) ? $product->id : '0' ?>
                            <input type="hidden" name="id" value="{{$id}}">
                            
                            <!-- Product Name -->
                            <div class="form-group">
                                <label for="product_name" class="col-md-2 control-label"> {{ trans('adminlabels.PRODUCT_NAME') }} </label>
                                <div class="col-md-6">
                                    <input id="product_name" type="text" class="form-control" name="product_name" value="{{ (old('product_name') ? old('product_name') : $product->product_name) }}" autofocus>
                                </div>
                            </div>

                            <!-- Product Type -->
                            <div class="form-group">
                                <label for="product_type" class="col-md-2 control-label"> {{ trans('adminlabels.PRODUCT_TYPE') }} </label>
                                <div class="col-md-6">
                                    <select class="form-control" id="product_type" name="product_type">
                                        @foreach ($productType as $_productType)
                                            <option value="{{$_productType['value']}}" @if($product->product_type == $_productType['value']) selected="selected" @endif >{{$_productType['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Product Year -->
                            <div class="form-group">
                                <label for="product_year" class="col-md-2 control-label"> {{ trans('adminlabels.PRODUCT_YEAR') }} </label>
                                <div class="col-md-6">
                                    <input id="product_year" type="text" class="form-control allownumericwithoutdecimal" name="product_year" value="{{ (old('product_year') ? old('product_year') : $product->product_year) }}" autofocus>
                                </div>
                            </div>


                            <!-- Product description -->
                            <div class="form-group">
                                <label for="product_description" class="col-md-2 control-label"> {{ trans('adminlabels.PRODUCT_DESCRIPTION') }} </label>
                                <div class="col-md-6">
                                    <textarea id="product_description" class="form-control" name="product_description" autofocus>{{ (old('product_description') ? old('product_description') : $product->product_description) }}</textarea>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="form-group">
                                <label for="status" class="col-md-2 control-label"> {{ trans('adminlabels.PRODUCT_STATUS') }} </label>
                                <div class="col-md-6">
                                    <select class="form-control" id="status" name="status">
                                        @foreach ($status as $_status)
                                            <option value="{{$_status['value']}}" @if($product->status == $_status['value']) selected="selected" @endif >{{$_status['name']}}</option>
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
                                    <a href="{{url('admin/product')}}" class="btn btn-primary">{{ trans('adminlabels.CANCEL') }}</a>
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
        
        $("#addUpdateProduct").validate({
            ignore: ":hidden:not(select)",
            rules: {
                product_name: {
                    required: true,
                    maxlength: 100
                },
                product_type: {
                    required: true
                },
                product_year: {
                    required: true,
                    digits:true,
                    max: 25
                },
                product_description: {
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