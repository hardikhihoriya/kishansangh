<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Config;
use App\Product;
use Redirect;
use Response;
use DB;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
    }

    public function index() {
        return view('admin.product.list');
    }

    /**
     * [listAjax List Product]
     * @param  [type]       [description]
     * @return [json]       [list of product]
     */
    public function listAjax(Request $request) {
        $records = array();
        //processing custom actions
        if ($request->customActionType == 'groupAction') {

            $action = $request->customActionName;
            $idArray = $request->id;

            if ($action == 'delete') {
                
            }
            if ($action == 'status') {
                foreach ($idArray as $_idArray) {
                    Product::updateProductStatus($_idArray);
                }
                $records["customMessage"] = trans('adminmsg.PRODUCT_STATUS_UPDATED_SUCCESS');
            }
        }

        $columns = array(
            0 => 'product_name',
            1 => 'product_type',
            2 => 'product_year',
            3 => 'status'
        );

        $order = $request->order;
        $search = $request->search;
        $records["data"] = array();

        //getting records from the product table
        $iTotalRecords = Product::getProductCount();
        $iTotalFiltered = $iTotalRecords;
        $iDisplayLength = intval($request->length) <= 0 ? $iTotalRecords : intval($request->length);
        $iDisplayStart = intval($request->start);
        $sEcho = intval($request->draw);

        $records["data"] = Product::where('status', '<>', Config::get('constant.DELETED_FLAG'));

        if (!empty($search['value'])) {
            $val = $search['value'];
            $records["data"]->where(function($query) use ($val) {
                $query->SearchProductName($val)
                        ->SearchProductType($val)
                        ->SearchProductYear($val)
                        ->SearchProductStatus($val);
            });

            // No of record after filtering
            $iTotalFiltered = $records["data"]->where(function($query) use ($val) {
                $query->SearchProductName($val)
                        ->SearchProductType($val)
                        ->SearchProductYear($val)
                        ->SearchProductStatus($val);
                    })->count();
        }

        //order by
        foreach ($order as $o) {
            $records["data"] = $records["data"]->orderBy($columns[$o['column']], $o['dir']);
        }

        //limit
        $records["data"] = $records["data"]->take($iDisplayLength)->offset($iDisplayStart)->get([
            'id',
            'product_name',
            'product_type',
            'product_year',
            'status'
        ]);

        if (!empty($records["data"])) {
            foreach ($records["data"] as $key => $_records) {
                $edit = route('product.edit', $_records->id);

                if ($_records->status == "active") {
                    $records["data"][$key]->status = '<a href="javascript:;" title="Make Inactive" class="btn-status-product" data-id="' . $_records->id . '"> ' . $_records->status . '</a>';
                } else {
                    $records["data"][$key]->status = '<a href="javascript:;" title="Make Active" class="btn-status-product" data-id="' . $_records->id . '"> ' . $_records->status . '</a>';
                }
                $records["data"][$key]['action'] = "&emsp;<a href='{$edit}' title='Edit Product' ><span class='glyphicon glyphicon-edit'></span></a>
                                                    &emsp;<a href='javascript:;' data-id='" . $_records->id . "' class='btn-delete-product' title='Delete Product' ><span class='glyphicon glyphicon-trash'></span></a>";
            }
        }
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalFiltered;

        return Response::json($records);
    }

    /**
     * Create controller
     */
    public function create() {
        return $this->_update();
    }

    /**
     * Update controller
     * @param $id = product id
     */
    public function update($id) {
        $product = Product::find($id);
        if (!$product) {
            return Redirect::to("/admin/product/")->with('error', trans('adminmsg.PRODUCT_NOT_EXIST'));
        }
        return $this->_update($product);
    }

    /**
     * Create/Update controller
     * @param $product = product object
     */
    private function _update($product = null) {
        if ($product === null) {
            $product = new Product;
        }

        $status = Config::get('constant.STATUS');
        $productType = Config::get('constant.PRODUCT_TYPE');
        return view('admin.product.add', compact('product', 'status', 'productType'));
    }

    /**
     * @description: save product data
     * @return type
     */
    public function set(Request $request) {
        try {
            $rule = [
                'product_name' => 'required|max:100',
                'product_type' => 'required',
                'product_year' => 'required|integer|min:1|max:25',
                'product_description' => 'required|max:500',
                'status' => 'required'
            ];

            $this->validate(request(), $rule);

            DB::beginTransaction();
            $product = Product::find($request->id);

            $postData = $request->only('product_name', 'product_type', 'product_year', 'product_description', 'status');

            if (isset($request->id) && $request->id > 0) {
                $product->update(array_filter($postData));
                $product->save();
                DB::commit();
                return Redirect::to("/admin/product/")->with('success', trans('adminmsg.PRODUCT_UPDATED_SUCCESS_MSG'));
            } else {
                $product = new Product(array_filter($postData));
                $product->save();
                
                DB::commit();
                return Redirect::to("/admin/product/")->with('success', trans('adminmsg.PRODUCT_CREATED_SUCCESS_MSG'));
            }
        } catch (Exception $e) {
            DB::rollback();
            return Redirect::to("/admin/product/")->with('error', trans('adminmsg.COMMON_ERROR_MSG'));
        }
    }

}
