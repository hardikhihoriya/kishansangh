<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Config;
use App\ShopMarketing;
use Redirect;
use Response;
use DB;

class ShopMarketingController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
    }

    public function index() {
        return view('admin.shop-marketing.list');
    }

    /**
     * [listAjax List Shop Marketing]
     * @param  [type]       [description]
     * @return [json]       [list of shop marketing]
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
                    ShopMarketing::updateShopMarketingStatus($_idArray);
                }
                $records["customMessage"] = trans('adminmsg.SHOP_MARKETING_STATUS_UPDATED_SUCCESS');
            }
        }

        $columns = array(
            0 => 'shop_marketing_name',
            1 => 'member',
            2 => 'description',
            3 => 'status'
        );

        $order = $request->order;
        $search = $request->search;
        $records["data"] = array();

        //getting records for the users table
        $iTotalRecords = ShopMarketing::getShopMarketingCount();
        $iTotalFiltered = $iTotalRecords;
        $iDisplayLength = intval($request->length) <= 0 ? $iTotalRecords : intval($request->length);
        $iDisplayStart = intval($request->start);
        $sEcho = intval($request->draw);

        $records["data"] = ShopMarketing::where('status', '<>', Config::get('constant.DELETED_FLAG'));

        if (!empty($search['value'])) {
            $val = $search['value'];
            $records["data"]->where(function($query) use ($val) {
                $query->SearchShopMarketingName($val)
                        ->SearchShopMarketingMember($val)
                        ->SearchShopMarketingDetail($val)
                        ->SearchShopMarketingStatus($val);
            });

            // No of record after filtering
            $iTotalFiltered = $records["data"]->where(function($query) use ($val) {
                $query->SearchShopMarketingName($val)
                        ->SearchShopMarketingMember($val)
                        ->SearchShopMarketingDetail($val)
                        ->SearchShopMarketingStatus($val);
            })->count();
        }

        //order by
        foreach ($order as $o) {
            $records["data"] = $records["data"]->orderBy($columns[$o['column']], $o['dir']);
        }

        //limit
        $records["data"] = $records["data"]->take($iDisplayLength)->offset($iDisplayStart)->get();

        if (!empty($records["data"])) {
            foreach ($records["data"] as $key => $_records) {
                $edit = route('shop-marketing.edit', $_records->id);

                if ($_records->status == "active") {
                    $records["data"][$key]->status = '<a href="javascript:;" title="Make Inactive" class="btn-status-shop-marketing" data-id="' . $_records->id . '"> ' . $_records->status . '</a>';
                } else {
                    $records["data"][$key]->status = '<a href="javascript:;" title="Make Active" class="btn-status-shop-marketing" data-id="' . $_records->id . '"> ' . $_records->status . '</a>';
                }
                $records["data"][$key]['action'] = "&emsp;<a href='{$edit}' title='Edit Shop Marketing' ><span class='glyphicon glyphicon-edit'></span></a>
                                                    &emsp;<a href='javascript:;' data-id='" . $_records->id . "' class='btn-delete-shop-marketing' title='Delete Shop Marketing' ><span class='glyphicon glyphicon-trash'></span></a>";
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
     * @param $id = shop marketing id
     */
    public function update($id) {
        $shopMarketing = ShopMarketing::find($id);
        if (!$shopMarketing) {
            return Redirect::to("/admin/shop-marketing/")->with('error', trans('adminmsg.SHOP_MARKETING_NOT_EXIST'));
        }
        return $this->_update($shopMarketing);
    }

    /**
     * Create/Update controller
     * @param $shopMarketing = shop marketing object
     */
    private function _update($shopMarketing = null) {
        if ($shopMarketing === null) {
            $shopMarketing = new ShopMarketing;
        }

        $status = Config::get('constant.STATUS');

        return view('admin.shop-marketing.add', compact('shopMarketing', 'status'));
    }

    /**
     * @description: save shop marketing data
     * @return type
     */
    public function set(Request $request) {

        try {
            $rule = [
                'shop_marketing_name' => 'required|max:100',
                'member' => 'required|regex:/^[0-9]+$/',
                'description' => 'required|max:500',
                'status' => 'required'
            ];

            $this->validate(request(), $rule);
            
            DB::beginTransaction();
            $shopMarketing = ShopMarketing::find($request->id);

            $postData = $request->only('shop_marketing_name', 'member', 'description', 'status');

            if (isset($request->id) && $request->id > 0) {
                $shopMarketing->update(array_filter($postData));
                $shopMarketing->save();
                DB::commit();
                return Redirect::to("/admin/shop-marketing/")->with('success', trans('adminmsg.SHOP_MARKETING_UPDATED_SUCCESS_MSG'));
            } else {
                $shopMarketing = new ShopMarketing(array_filter($postData));
                $shopMarketing->save();
                DB::commit();
                return Redirect::to("/admin/shop-marketing/")->with('success', trans('adminmsg.SHOP_MARKETING_CREATED_SUCCESS_MSG'));
            }
        } catch (Exception $e) {
            DB::rollback();
            return Redirect::to("/admin/shop-marketing/")->with('error', trans('adminmsg.COMMON_ERROR_MSG'));
        }
    }

}
