<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Config;
use App\Helpers\ImageUpload;
use App\ShopType;
use Redirect;
use File;
use Response;
use DB;

class ShopTypeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->shopTypeOriginalImageUploadPath = Config::get('constant.SHOP_TYPE_ORIGINAL_IMAGE_UPLOAD_PATH');
        $this->shopTypeThumbImageUploadPath = Config::get('constant.SHOP_TYPE_THUMB_IMAGE_UPLOAD_PATH');
        $this->shopTypeThumbImageHeight = Config::get('constant.SHOP_TYPE_THUMB_IMAGE_HEIGHT');
        $this->shopTypeThumbImageWidth = Config::get('constant.SHOP_TYPE_THUMB_IMAGE_WIDTH');
    }

    public function index() {
        return view('admin.shop-type.list');
    }
    
    /**
     * [listAjax List Shop Type]
     * @param  [type]       [description]
     * @return [json]       [list of shop type]
     */
    public function listAjax(Request $request) {
        $records = array();
        //processing custom actions
        if ($request->customActionType == 'groupAction') {

            $action = $request->customActionName;
            $idArray = $request->id;

            if($action == 'delete') {
            }
            if($action == 'status') {
                foreach ($idArray as $_idArray) {
                    ShopType::updateShopTypeStatus($_idArray);
                }
                $records["customMessage"] = trans('adminmsg.SHOP_TYPE_STATUS_UPDATED_SUCCESS');
            }
        }
        
        $columns = array( 
            0 => 'shop_type_name',
            1 => 'shop_type_detail',
            2 => 'shop_type_icon',
            3 => 'status'
        );
        
        $order = $request->order;
        $search = $request->search;
        $records["data"] = array();
        
        //getting records for the users table
        $iTotalRecords = count(ShopType::getShopType());
        $iTotalFiltered = $iTotalRecords;
        $iDisplayLength = intval($request->length) <= 0 ? $iTotalRecords : intval($request->length);
        $iDisplayStart = intval($request->start);
        $sEcho = intval($request->draw);
        
        $records["data"] = ShopType::where('status', '<>', Config::get('constant.DELETED_FLAG'));
        
        if (!empty($search['value'])) {
            $val = $search['value'];
            $records["data"]->where(function($query) use ($val) {
                $query->SearchShopTypeName($val)
                    ->SearchShopTypeDetail($val)
                    ->scopeSearchShopTypeStatus($val);
            });
            
            // No of record after filtering
            $iTotalFiltered = $records["data"]->where(function($query) use ($val) {
                $query->SearchShopTypeName($val)
                    ->SearchShopTypeDetail($val)
                    ->scopeSearchShopTypeStatus($val);
            })->count();
        }
        
        //order by
        foreach ($order as $o) {
            $records["data"] = $records["data"]->orderBy($columns[$o['column']], $o['dir']);
        }

        //limit
        $records["data"] = $records["data"]->take($iDisplayLength)->offset($iDisplayStart)->get();

        if(!empty($records["data"])) {
            foreach ($records["data"] as $key => $_records) {
                $edit =  route('shop-type.edit', $_records->id);

                if ($_records->status == "active") {
                    $records["data"][$key]->status = '<a href="javascript:;" title="Make Inactive" class="btn-status-shop-type" data-id="' . $_records->id . '"> ' . $_records->status . '</a>';
                } else {
                    $records["data"][$key]->status = '<a href="javascript:;" title="Make Active" class="btn-status-shop-type" data-id="' . $_records->id . '"> ' . $_records->status . '</a>';
                }
                $records["data"][$key]['shop_type_icon'] = ($_records->shop_type_icon != '' && File::exists(public_path($this->shopTypeThumbImageUploadPath . $_records->shop_type_icon)) ? '<img src="'.url($this->shopTypeThumbImageUploadPath.$_records->shop_type_icon).'" alt="{{$_records->shop_type_icon}}"  height="50" width="50">' : '<img src="'.asset('/images/default.png').'" alt="Default Image" height="50" width="50">');
                $records["data"][$key]['action'] = "&emsp;<a href='{$edit}' title='Edit Shop Type' ><span class='glyphicon glyphicon-edit'></span></a>
                                                    &emsp;<a href='javascript:;' data-id='". $_records->id ."' class='btn-delete-shop-type' title='Delete Shop Type' ><span class='glyphicon glyphicon-trash'></span></a>";
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
     * @param $id = shop type id
     */
    public function update($id) {
        $shopType = ShopType::find($id);
        if(!$shopType) {
            return Redirect::to("/admin/shop-type/")->with('error', trans('adminmsg.SHOP_TYPE_NOT_EXIST'));
        }
        return $this->_update($shopType);
    }

    /**
     * Create/Update controller
     * @param $shopType = shop type object
     */
    private function _update($shopType = null) {
        if ($shopType === null) {
            $shopType = new ShopType;
        }
        
        $shopTypeIconPath = $this->shopTypeThumbImageUploadPath;
        $status = Config::get('constant.STATUS');
        
        return view('admin.shop-type.add', compact('shopType', 'shopTypeIconPath', 'status'));
    }

    /**
     * @description: save shop type data
     * @return type
     */
    public function set(Request $request) {
        
        try {
            $rule = [
                'shop_type_name' => 'required|max:100',
                'shop_type_detail' => 'required|max:500',
                'shop_type_icon' => 'mimes:png|jpeg|jpg|bmp|max:10240',
                'status' => 'required'
            ];

            if(!$request->id || empty($request->id) || $request->id == 0 ) {
                $rule['shop_type_icon'] = 'required|mimes:png|jpeg|jpg|bmp|max:10240';
            }
            
            $this->validate(request(), $rule);
            
            DB::beginTransaction();
            $shopType = ShopType::find($request->id);

            $postData = $request->only('shop_type_name', 'shop_type_detail', 'status');

            $postData['shop_type_icon'] = $request->hidden_shop_type_icon;

            // Upload User Image
            if (!empty($request->file('shop_type_icon')) && $request->file('shop_type_icon')->isValid()) {
                $params = [
                    'originalPath' => public_path($this->shopTypeOriginalImageUploadPath),
                    'thumbPath' => public_path($this->shopTypeThumbImageUploadPath),
                    'thumbHeight' => public_path($this->shopTypeThumbImageHeight),
                    'thumbWidth' => public_path($this->shopTypeThumbImageWidth),
                    'previousImage' => $request->hidden_shop_type_icon
                ];
                $shopTypeIcon = ImageUpload::uploadWithThumbImage($request->file('shop_type_icon'), $params);
                if($shopTypeIcon === FALSE) {
                    DB::rollback();
                    return Redirect::back()->withInput($request->all())->withErrors([
                        trans('adminmsg.SHOP_TYPE_IMAGE_UPLOAD_ERROR_MSG')
                    ]);
                }
                $postData['shop_type_icon'] = $shopTypeIcon['imageName'];
            }

            if (isset($request->id) && $request->id > 0) {
                $shopType->update(array_filter($postData));
                $shopType->save();
                DB::commit();
                return Redirect::to("/admin/shop-type/")->with('success', trans('adminmsg.SHOP_TYPE_UPDATED_SUCCESS_MSG'));
            } else {
                $shopType = new ShopType(array_filter($postData));
                $shopType->save();
                DB::commit();
                return Redirect::to("/admin/shop-type/")->with('success', trans('adminmsg.SHOP_TYPE_CREATED_SUCCESS_MSG'));  
            }
        } catch (Exception $e) {
            DB::rollback();
            return Redirect::to("/admin/shop-type/")->with('error', trans('adminmsg.COMMON_ERROR_MSG'));  
        }
    }
}
