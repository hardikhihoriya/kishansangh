<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Config;
use App\Helpers\ImageUpload;
use App\Helpers\Common;
use App\User;
use App\Role;
use App\RoleUser;
use Redirect;
use File;
use Response;
use DB;

class VendorController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
    }

    public function index() {
        return view('admin.vendors.list');
    }

    /**
     * [listAjax List Customers]
     * @param  [type]       [description]
     * @return [json]       [list of users]
     */
    public function listAjax(Request $request) {
        $records = array();
        //processing custom actions
        if ($request->customActionType == 'groupAction') {

            $action = $request->customActionName;
            $idArray = $request->id;

            switch ($action) {
                case "delete":
            }
        }

        $columns = array(
            0 => 'registration_no',
            1 => 'first_name',
            2 => 'email',
            3 => 'phone_no',
            4 => 'birth_date',
            5 => 'gender',
            6 => 'vendor_name'
        );

        $order = $request->order;
        $search = $request->search;
        $records["data"] = array();

        //getting records for the users table
        $vendorRole = Role::find(Config::get('constant.VENDOR'));

        $iTotalRecords = Common::getTotalCount($vendorRole);
        $iTotalFiltered = $iTotalRecords;
        $iDisplayLength = intval($request->length) <= 0 ? $iTotalRecords : intval($request->length);
        $iDisplayStart = intval($request->start);
        $sEcho = intval($request->draw);

        $records["data"] = User::join('role_user', 'role_user.user_id', '=', 'users.id')
                ->where('users.deleted', '<>', Config::get('constant.DELETED_FLAG'))
                ->where('role_user.role_id', Config::get('constant.VENDOR'));

        if (!empty($search['value'])) {
            $val = $search['value'];
            $records["data"]->where(function($query) use ($val) {
                $query->SearchName($val)
                        ->SearchRegistrationNo($val)
                        ->SearchEmail($val)
                        ->SearchPhone($val)
                        ->SearchBirthDate($val)
                        ->SearchGender($val)
                        ->SearchVendorName($val);
            });

            // No of record after filtering
            $iTotalFiltered = $records["data"]->where(function($query) use ($val) {
                        $query->SearchName($val)
                                ->SearchRegistrationNo($val)
                                ->SearchEmail($val)
                                ->SearchPhone($val)
                                ->SearchBirthDate($val)
                                ->SearchGender($val)
                                ->SearchVendorName($val);
                    })->count();
        }

        //order by
        foreach ($order as $o) {
            $records["data"] = $records["data"]->orderBy($columns[$o['column']], $o['dir']);
        }

        //limit
        $records["data"] = $records["data"]->take($iDisplayLength)->offset($iDisplayStart)->get([
            'role_user.id',
            'role_user.user_id',
            'role_user.vendor_name',
            'users.email',
            'users.first_name',
            'users.middle_name',
            'users.last_name',
            'users.registration_no',
            'users.phone_no',
            'users.birth_date',
            'users.gender'
        ]);

        if (!empty($records["data"])) {
            foreach ($records["data"] as $key => $_records) {
                $edit = route('vendor.edit', $_records->id);

                $records["data"][$key]['name'] = $_records->first_name . (!empty($_records->middle_name) ? ' ' . $_records->middle_name : '') . (!empty($_records->last_name) ? ' ' . $_records->last_name : '');
                $records["data"][$key]['action'] = "&emsp;<a href='{$edit}' title='Add Customer Detail' ><span class='glyphicon glyphicon-plus'></span></a>";
                if ($records["data"][$key]['vendor_name'] != '') {
                    $records["data"][$key]['action'] = "&emsp;<a href='{$edit}' title='Edit Vendor Detail' ><span class='glyphicon glyphicon-edit'></span></a>
                                                        &emsp;<a href='javascript:;' data-id='" . $_records->user_id . "' class='btn-view-vendor' title='View Vendor Detail' ><span class='glyphicon glyphicon-eye-open'></span></a>
                                                        &emsp;<a href='javascript:;' data-id='" . $_records->user_id . "' class='btn-delete-vendor' title='Delete Vendor' ><span class='glyphicon glyphicon-trash'></span></a>";
                }
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
     * @param $id = user id
     */
    public function update($id) {
        $vendor = RoleUser::find($id);
        if (!$vendor) {
            return Redirect::to("/admin/vendor/")->with('error', trans('adminmsg.VENDOR_NOT_EXIST'));
        }
        return $this->_update($vendor);
    }

    /**
     * Create/Update controller
     * @param $vendor = vendor object
     */
    private function _update($vendor = null) {
        if ($vendor === null) {
            $vendor = new RoleUser;
        }

        $vendorRoleDetail = Role::find(Config::get('constant.VENDOR'));
        $users = Common::getUserWithoutGivenRole($vendorRoleDetail);

        return view('admin.vendors.add', compact('vendor', 'users'));
    }

    /**
     * @description: save user data
     * @return type
     */
    public function set(Request $request) {

        try {
            $rule = [
                'vendor_name' => 'required|max:100',
                'vendor_address' => 'required|max:500'
            ];

            if(!$request->id || empty($request->id) || $request->id == 0 ) {
                $rule['user_id'] = 'required';
            }
            
            $this->validate(request(), $rule);
            
            DB::beginTransaction();
            $vendor = RoleUser::find($request->id);

            $postData = $request->only('vendor_name', 'vendor_address');

            if (isset($request->id) && $request->id > 0) {
                $vendor->update(array_filter($postData));
                $vendor->save();
                DB::commit();
                return Redirect::to("/admin/vendor/")->with('success', trans('adminmsg.VENDOR_DETAIL_UPDATED_SUCCESS_MSG'));
            } else {
                $userData = User::find($request->user_id);
                if ($userData == null) {
                    DB::rollback();
                    return Redirect::back()->withInput($request->all())->withErrors([
                                trans('adminmsg.USER_NOT_EXIST')
                    ]);
                }
                if ($userData && $userData->deleted != Config::get('constant.ACTIVE_FLAG')) {
                    DB::rollback();
                    return Redirect::back()->withInput($request->all())->withErrors([
                                trans('adminmsg.USER_INACTIVE_DELETE')
                    ]);
                }

                // Assign role
                $userData->roles()->attach(Config::get('constant.VENDOR'), $postData);
                DB::commit();
                return Redirect::to("/admin/vendor/")->with('success', trans('adminmsg.VENDOR_DETAIL_ADDED_SUCCESS_MSG'));
            }
        } catch (Exception $e) {
            DB::rollback();
            return Redirect::to("/admin/vendor/")->with('error', trans('adminmsg.COMMON_ERROR_MSG'));
        }
    }

}
