<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Config;
use App\ShopPackage;
use App\ShopMarketing;
use Redirect;
use Response;
use DB;

class ShopPackageController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        
    }

    public function index() {
        return view('admin.shop-package.list');
    }

    /**
     * [listAjax List Shop Package]
     * @param  [type]       [description]
     * @return [json]       [list of shop package]
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
                    ShopPackage::updateShopPackageStatus($_idArray);
                }
                $records["customMessage"] = trans('adminmsg.SHOP_PACKAGE_STATUS_UPDATED_SUCCESS');
            }
        }

        $columns = array(
            0 => 'package_name',
            1 => 'shop_marketing_name',
            2 => 'boosting_point',
            3 => 'per_day_SMS',
            4 => 'package_description',
            5 => 'price',
            6 => 'status'
        );

        $order = $request->order;
        $search = $request->search;
        $records["data"] = array();

        //getting records for the users table
        $iTotalRecords = count(ShopPackage::getShopPackage());
        $iTotalFiltered = $iTotalRecords;
        $iDisplayLength = intval($request->length) <= 0 ? $iTotalRecords : intval($request->length);
        $iDisplayStart = intval($request->start);
        $sEcho = intval($request->draw);

        $records["data"] = ShopPackage::leftJoin('shop_marketing', 'shop_marketing.id', '=', 'shop_package.shop_marketing_id')
                            ->where('shop_package.status', '<>', Config::get('constant.DELETED_FLAG'));

        if (!empty($search['value'])) {
            $val = $search['value'];
            $records["data"]->where(function($query) use ($val) {
                $query->SearchShopPackageName($val)
                        ->SearchBoostingPoint($val)
                        ->SearchPerDaySMS($val)
                        ->SearchDescription($val)
                        ->SearchPrice($val)
                        ->SearchStatus($val);
            });

            // No of record after filtering
            $iTotalFiltered = $records["data"]->where(function($query) use ($val) {
                $query->SearchShopPackageName($val)
                        ->SearchBoostingPoint($val)
                        ->SearchPerDaySMS($val)
                        ->SearchDescription($val)
                        ->SearchPrice($val)
                        ->SearchStatus($val);
                    })->count();
        }

        //order by
        foreach ($order as $o) {
            $records["data"] = $records["data"]->orderBy($columns[$o['column']], $o['dir']);
        }

        //limit
        $records["data"] = $records["data"]->take($iDisplayLength)->offset($iDisplayStart)->get([
            'shop_package.*',
            'shop_marketing.shop_marketing_name'
        ]);
        
        if (!empty($records["data"])) {
            foreach ($records["data"] as $key => $_records) {
                $edit = route('shop-package.edit', $_records->id);

                if ($_records->status == "active") {
                    $records["data"][$key]->status = '<a href="javascript:;" title="Make Inactive" class="btn-status-shop-package" data-id="' . $_records->id . '"> ' . $_records->status . '</a>';
                } else {
                    $records["data"][$key]->status = '<a href="javascript:;" title="Make Active" class="btn-status-shop-package" data-id="' . $_records->id . '"> ' . $_records->status . '</a>';
                }
                $records["data"][$key]['action'] = "&emsp;<a href='{$edit}' title='Edit Shop Package' ><span class='glyphicon glyphicon-edit'></span></a>
                                                    &emsp;<a href='javascript:;' data-id='" . $_records->id . "' class='btn-delete-shop-package' title='Delete Shop Package' ><span class='glyphicon glyphicon-trash'></span></a>";
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
     * @param $id = shop package id
     */
    public function update($id) {
        $shopPackage = ShopPackage::find($id);
        if (!$shopPackage) {
            return Redirect::to("/admin/shop-package/")->with('error', trans('adminmsg.SHOP_PACKAGE_NOT_EXIST'));
        }
        return $this->_update($shopPackage);
    }

    /**
     * Create/Update controller
     * @param $shopPackage = shop package object
     */
    private function _update($shopPackage = null) {
        if ($shopPackage === null) {
            $shopPackage = new ShopPackage;
        }

        $shopMarketingList = ShopMarketing::getShopMarketing();
        $status = Config::get('constant.STATUS');

        return view('admin.shop-package.add', compact('shopPackage', 'shopMarketingList', 'status'));
    }

    /**
     * @description: save shop package data
     * @return type
     */
    public function set(Request $request) {

        try {
            $rule = [
                'shop_marketing_id' => 'required',
                'package_name' => 'required|max:100',
                'boosting_point' => 'required|numeric',
                'per_day_SMS' => 'required|regex:/^[0-9]+$/',
                'price' => 'required|numeric',
                'description' => 'required|max:500',
                'status' => 'required'
            ];

            $this->validate(request(), $rule);
            
            DB::beginTransaction();
            $shopPackage = ShopPackage::find($request->id);

            $postData = $request->only('shop_marketing_id', 'package_name', 'price', 'boosting_point', 'package_description', 'per_day_SMS', 'status');

            if (isset($request->id) && $request->id > 0) {
                $shopPackage->update(array_filter($postData));
                $shopPackage->save();
                DB::commit();
                return Redirect::to("/admin/shop-package/")->with('success', trans('adminmsg.SHOP_PACKAGE_UPDATED_SUCCESS_MSG'));
            } else {
                $shopPackage = new ShopPackage(array_filter($postData));
                $shopPackage->save();
                DB::commit();
                return Redirect::to("/admin/shop-package/")->with('success', trans('adminmsg.SHOP_PACKAGE_CREATED_SUCCESS_MSG'));
            }
        } catch (Exception $e) {
            DB::rollback();
            return Redirect::to("/admin/shop-package/")->with('error', trans('adminmsg.COMMON_ERROR_MSG'));
        }
    }

}
