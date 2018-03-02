<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Config;
use App\Shop;
use App\ShopTime;
use App\ShopType;
use App\ShopPackage;
use App\Role;
use Redirect;
use File;
use App\Helpers\ImageUpload;
use App\Helpers\Common;
use Response;
use DB;

class ShopController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->shopOriginalImageUploadPath = Config::get('constant.SHOP_ORIGINAL_IMAGE_UPLOAD_PATH');
        $this->shopThumbImageUploadPath = Config::get('constant.SHOP_THUMB_IMAGE_UPLOAD_PATH');
        $this->shopThumbImageHeight = Config::get('constant.SHOP_THUMB_IMAGE_HEIGHT');
        $this->shopThumbImageWidth = Config::get('constant.SHOP_THUMB_IMAGE_WIDTH');
    }

    public function index() {
        return view('admin.shop.list');
    }

    /**
     * [listAjax List Shop]
     * @param  [type]       [description]
     * @return [json]       [list of shop]
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
                    Shop::updateShopStatus($_idArray);
                }
                $records["customMessage"] = trans('adminmsg.SHOP_STATUS_UPDATED_SUCCESS');
            }
        }

        $columns = array(
            0 => 'shop_registration_no',
            1 => 'user_name',
            2 => 'vendor_name',
            3 => 'shop_type_name',
            4 => 'package_name',
            5 => 'shop_name',
            6 => 'shop_email',
            7 => 'shop_phone_no',
            8 => 'shop_anniversary_date',
            9 => 'zipcode',
            10 => 'shop_logo',
            11 => 'status'
        );

        $order = $request->order;
        $search = $request->search;
        $records["data"] = array();

        //getting records from the shop table
        $iTotalRecords = Shop::getShopCount();
        $iTotalFiltered = $iTotalRecords;
        $iDisplayLength = intval($request->length) <= 0 ? $iTotalRecords : intval($request->length);
        $iDisplayStart = intval($request->start);
        $sEcho = intval($request->draw);

        $records["data"] = Shop::leftJoin('users', 'users.id', '=', 'shop.user_id')
                ->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
                ->leftJoin('shop_type', 'shop_type.id', '=', 'shop.shop_type_id')
                ->leftJoin('shop_package', 'shop_package.id', '=', 'shop.shop_package_id')
                ->where('role_user.role_id', Config::get('constant.VENDOR'))
                ->where('shop.status', '<>', Config::get('constant.DELETED_FLAG'))
                ->where('role_user.status', '<>', Config::get('constant.DELETED_FLAG'));

        if (!empty($search['value'])) {
            $val = $search['value'];
            $records["data"]->where(function($query) use ($val) {
                $query->SearchShopRegistrationNo($val)
                        ->SearchUserName($val)
                        ->SearchVendorName($val)
                        ->SearchShopType($val)
                        ->SearchShopPackage($val)
                        ->SearchShopName($val)
                        ->SearchShopEmail($val)
                        ->SearchShopPhone($val)
                        ->SearchShopAnniverssaryDate($val)
                        ->SearchShopZipcode($val)
                        ->SearchShopStatus($val);
            });

            // No of record after filtering
            $iTotalFiltered = $records["data"]->where(function($query) use ($val) {
                        $query->SearchShopRegistrationNo($val)
                                ->SearchUserName($val)
                                ->SearchVendorName($val)
                                ->SearchShopType($val)
                                ->SearchShopPackage($val)
                                ->SearchShopName($val)
                                ->SearchShopEmail($val)
                                ->SearchShopPhone($val)
                                ->SearchShopAnniverssaryDate($val)
                                ->SearchShopZipcode($val)
                                ->SearchShopStatus($val);
                    })->count();
        }

        //order by
        foreach ($order as $o) {
            $records["data"] = $records["data"]->orderBy($columns[$o['column']], $o['dir']);
        }

        //limit
        $records["data"] = $records["data"]->take($iDisplayLength)->offset($iDisplayStart)->get([
            'shop.*',
            'shop_type.shop_type_name',
            'shop_package.package_name',
            'role_user.vendor_name',
            DB::raw("CONCAT(users.first_name, ' ', users.middle_name, ' ', users.last_name) AS user_name"),
            'shop.status'
        ]);

        if (!empty($records["data"])) {
            foreach ($records["data"] as $key => $_records) {
                $edit = route('shop.edit', $_records->id);

                if ($_records->status == "active") {
                    $records["data"][$key]->status = '<a href="javascript:;" title="Make Inactive" class="btn-status-shop" data-id="' . $_records->id . '"> ' . $_records->status . '</a>';
                } else {
                    $records["data"][$key]->status = '<a href="javascript:;" title="Make Active" class="btn-status-shop" data-id="' . $_records->id . '"> ' . $_records->status . '</a>';
                }
                $records["data"][$key]['shop_logo'] = ($_records->shop_logo != '' && File::exists(public_path($this->shopThumbImageUploadPath . $_records->shop_logo)) ? '<img src="' . url($this->shopThumbImageUploadPath . $_records->shop_logo) . '" alt="{{$_records->shop_logo}}"  height="50" width="50">' : '<img src="' . asset('/images/default.png') . '" alt="Default Image" height="50" width="50">');
                $records["data"][$key]['action'] = "&emsp;<a href='{$edit}' title='Edit Shop' ><span class='glyphicon glyphicon-edit'></span></a>
                                                    &emsp;<a href='javascript:;' data-id='" . $_records->id . "' class='btn-delete-shop' title='Delete Shop' ><span class='glyphicon glyphicon-trash'></span></a>";
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
     * @param $id = shop id
     */
    public function update($id) {
        $shop = Shop::find($id);
        $shopWithTime = $shop->with('shopTime')->where('id', $id)->get();
        if (!$shop) {
            return Redirect::to("/admin/shops/")->with('error', trans('adminmsg.SHOP_NOT_EXIST'));
        }
        return $this->_update($shopWithTime);
    }

    /**
     * Create/Update controller
     * @param $shop = shop object
     */
    private function _update($shop = null) {
        if ($shop === null) {
            $shop = new Shop;
        }

        $shopIconPath = $this->shopThumbImageUploadPath;
        $status = Config::get('constant.STATUS');
        $dayList = Config::get('constant.DAYS');
        $shopType = ShopType::getShopType();
        $shopPackage = ShopPackage::getShopPackage();
        
        $vendorRoleDetail = Role::find(Config::get('constant.VENDOR'));
        $users = $vendorRoleDetail->users()->where('deleted', '<>', Config::get('constant.DELETED_FLAG'))->get();
        
        return view('admin.shop.add', compact('shop', 'shopIconPath', 'status', 'dayList', 'shopType', 'shopPackage', 'users'));
    }

    /**
     * @description: save shop data
     * @return type
     */
    public function set(Request $request) {
        try {
            $rule = [
                'shop_type_id' => 'required',
                'shop_package_id' => 'required',
                'shop_name' => 'required|max:100',
                'shop_email' => 'required|email|max:100',
                'shop_web_url' => array('required', 'max:255', 'regex:/\b(?:(?:https?|ftp):\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/'),
                'shop_phone_no' => 'required|max:10|regex:/^[0-9]+$/',
                'shop_logo' => 'mimes:png,jpeg,jpg,bmp|max:10240',
                'shop_anniversary_date' => 'required|date|date_format:Y-m-d',
                'address' => 'required|max:500',
                'zipcode' => 'required|regex:/^([1-9])([0-9]){5}$/',
                'status' => 'required'
            ];

            if (!$request->id || empty($request->id) || $request->id == 0) {
                $rule['user_id'] = 'required';
                $rule['shop_logo'] = 'required|mimes:png,jpeg,jpg,bmp|max:10240';
            }
            
            $required = true;
            foreach ($request->start_time as $time => $start_time) {
                if($request->close_time[$time] != '') {
                    $required = false;
                    $rule['start_time.'.$time] = 'required';
                }
                if($start_time != '') {
                    $required = false;
                    $rule['close_time.'.$time] = 'required';
                }
            }

            $this->validate(request(), $rule);

            if ($required === TRUE) {
                return Redirect::back()->withInput($request->all())->withErrors([
                            trans('adminmsg.SHOP_TIME_REQUIRED')
                ]);
            }

            DB::beginTransaction();

            $shop = Shop::find($request->id);

            $postData = $request->only('shop_type_id', 'shop_package_id', 'shop_name', 'shop_registration_no', 'shop_email', 'shop_phone_no', 'shop_anniversary_date', 'address', 'zipcode', 'status');

            $postData['shop_logo'] = $request->hidden_shop_logo;

            // Upload Shop Logo
            if (!empty($request->file('shop_logo')) && $request->file('shop_logo')->isValid()) {
                $params = [
                    'originalPath' => public_path($this->shopOriginalImageUploadPath),
                    'thumbPath' => public_path($this->shopThumbImageUploadPath),
                    'thumbHeight' => public_path($this->shopThumbImageHeight),
                    'thumbWidth' => public_path($this->shopThumbImageWidth),
                    'previousImage' => $request->hidden_shop_logo
                ];
                $shopIcon = ImageUpload::uploadWithThumbImage($request->file('shop_logo'), $params);
                if ($shopIcon === FALSE) {
                    DB::rollback();
                    return Redirect::back()->withInput($request->all())->withErrors([
                                trans('adminmsg.SHOP_IMAGE_UPLOAD_ERROR_MSG')
                    ]);
                }
                $postData['shop_logo'] = $shopIcon['imageName'];
            }

            $postData['shop_web_url'] = ($request->shop_web_url && $request->shop_web_url != '') ? Common::addhttp($request->shop_web_url) : '';
            
            if (isset($request->id) && $request->id > 0) {
                $shop->update(array_filter($postData));
                $shop->save();
                $shop->shopTime()->delete();
                $time = [];
                foreach (Config::get('constant.DAYS') as $day => $_day) {
                    if($request->start_time[$day] && $request->close_time[$day] && $request->start_time[$day] != '' && $request->close_time[$day] != '') {
                        array_push($time, new ShopTime([
                            'day' => $_day['value'],
                            'start_time' => $request->start_time[$day],
                            'close_time' => $request->close_time[$day]
                        ]));
                    }
                }                
                $shop->shopTime()->saveMany($time);
                DB::commit();
                return Redirect::to("/admin/shops/")->with('success', trans('adminmsg.SHOP_UPDATED_SUCCESS_MSG'));
            } else {
                $shopData = new Shop();
                
                $postData['user_id'] = $request->user_id;
                $postData['shop_registration_no'] = $shopData->generateRegistrationNo();
                $shop = new Shop(array_filter($postData));
                $shop->save();
                
                $time = [];
                foreach (Config::get('constant.DAYS') as $day => $_day) {
                    if($request->start_time[$day] && $request->close_time[$day] && $request->start_time[$day] != '' && $request->close_time[$day] != '') {
                        array_push($time, new ShopTime([
                            'day' => $_day['value'],
                            'start_time' => $request->start_time[$day],
                            'close_time' => $request->close_time[$day]
                        ]));
                    }
                }
                $shop->shopTime()->saveMany($time);
                
                DB::commit();
                return Redirect::to("/admin/shops/")->with('success', trans('adminmsg.SHOP_CREATED_SUCCESS_MSG'));
            }
        } catch (Exception $e) {
            DB::rollback();
            return Redirect::to("/admin/shops/")->with('error', trans('adminmsg.COMMON_ERROR_MSG'));
        }
    }

}
