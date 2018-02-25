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

class CustomerController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->customerOriginalImageUploadPath = Config::get('constant.CUSTOMER_ORIGINAL_IMAGE_UPLOAD_PATH');
        $this->customerThumbImageUploadPath = Config::get('constant.CUSTOMER_THUMB_IMAGE_UPLOAD_PATH');
        $this->customerThumbImageHeight = Config::get('constant.CUSTOMER_THUMB_IMAGE_HEIGHT');
        $this->customerThumbImageWidth = Config::get('constant.CUSTOMER_THUMB_IMAGE_WIDTH');

        $this->customerProofFrontOriginalImageUploadPath = Config::get('constant.CUSTOMER_PROOF_FRONT_ORIGINAL_IMAGE_UPLOAD_PATH');
        $this->customerProofFrontThumbImageUploadPath = Config::get('constant.CUSTOMER_PROOF_FRONT_THUMB_IMAGE_UPLOAD_PATH');
        $this->customerProofFrontThumbImageHeight = Config::get('constant.CUSTOMER_PROOF_FRONT_THUMB_IMAGE_HEIGHT');
        $this->customerProofFrontThumbImageWidth = Config::get('constant.CUSTOMER_PROOF_FRONT_THUMB_IMAGE_WIDTH');

        $this->customerProofBackOriginalImageUploadPath = Config::get('constant.CUSTOMER_PROOF_BACK_ORIGINAL_IMAGE_UPLOAD_PATH');
        $this->customerProofBackThumbImageUploadPath = Config::get('constant.CUSTOMER_PROOF_BACK_THUMB_IMAGE_UPLOAD_PATH');
        $this->customerProofBackThumbImageHeight = Config::get('constant.CUSTOMER_PROOF_BACK_THUMB_IMAGE_HEIGHT');
        $this->customerProofBackThumbImageWidth = Config::get('constant.CUSTOMER_PROOF_BACK_THUMB_IMAGE_WIDTH');
    }

    public function index() {
        return view('admin.customers.list');
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
//                    foreach ($idArray as $_idArray) {
//                        $user = User::find($_idArray);
//                        $user->fill(array_filter(['deleted' => Config::get('constant.DELETED_FLAG')]));
//                        $user->save();
//                    }
//                    $records["customMessage"] = trans('adminmsg.DELETE_USER');
            }
        }

        $columns = array(
            0 => 'registration_no',
            1 => 'name',
            2 => 'email',
            3 => 'phone_no',
            4 => 'birth_date',
            5 => 'gender',
            6 => 'customer_wallet'
        );

        $order = $request->order;
        $search = $request->search;
        $records["data"] = array();

        //getting records for the users table
        $customerRole = Role::find(Config::get('constant.CUSTOMER'));

        $iTotalRecords = Common::getTotalCount($customerRole);
        $iTotalFiltered = $iTotalRecords;
        $iDisplayLength = intval($request->length) <= 0 ? $iTotalRecords : intval($request->length);
        $iDisplayStart = intval($request->start);
        $sEcho = intval($request->draw);

        $records["data"] = User::join('role_user', 'role_user.user_id', '=', 'users.id')
                                ->where('users.deleted', '<>', Config::get('constant.DELETED_FLAG'))
                                ->where('role_user.role_id', Config::get('constant.CUSTOMER'));

        if (!empty($search['value'])) {
            $val = $search['value'];
            $records["data"]->where(function($query) use ($val) {
                $query->SearchName($val)
                        ->SearchRegistrationNo($val)
                        ->SearchEmail($val)
                        ->SearchPhone($val)
                        ->SearchBirthDate($val)
                        ->SearchGender($val)
                        ->SearchWalletAmount($val);
            });

            // No of record after filtering
            $iTotalFiltered = $records["data"]->where(function($query) use ($val) {
                        $query->SearchName($val)
                                ->SearchRegistrationNo($val)
                                ->SearchEmail($val)
                                ->SearchPhone($val)
                                ->SearchBirthDate($val)
                                ->SearchGender($val)
                                ->SearchWalletAmount($val);
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
            'role_user.customer_wallet',
            'users.email',
            DB::raw("CONCAT(users.first_name, ' ', users.middle_name, ' ', users.last_name) AS name"),
            'users.registration_no',
            'users.phone_no',
            'users.birth_date',
            'users.gender',
            'role_user.nominee_photo',
            'role_user.nominee_name'
        ]);

        if (!empty($records["data"])) {
            foreach ($records["data"] as $key => $_records) {
                $edit = route('customer.edit', $_records->id);

                $records["data"][$key]['nominee_photo'] = ($_records->nominee_photo != '' && File::exists(public_path($this->customerThumbImageUploadPath . $_records->nominee_photo)) ? '<img src="' . url($this->customerThumbImageUploadPath . $_records->nominee_photo) . '" alt="{{$_records->nominee_photo}}"  height="50" width="50">' : '<img src="' . asset('/images/default.png') . '" class="user-image" alt="Default Image" height="50" width="50">');
                $records["data"][$key]['action'] = "&emsp;<a href='{$edit}' title='Add Customer Detail' ><span class='glyphicon glyphicon-plus'></span></a>";
                if($records["data"][$key]['nominee_name'] != '') {
                    $records["data"][$key]['action'] = "&emsp;<a href='{$edit}' title='Edit Customer Detail' ><span class='glyphicon glyphicon-edit'></span></a>
                                                        &emsp;<a href='javascript:;' data-id='" . $_records->user_id . "' class='btn-view-customer' title='View Customer Detail' ><span class='glyphicon glyphicon-eye-open'></span></a>
                                                        &emsp;<a href='javascript:;' data-id='" . $_records->user_id . "' class='btn-delete-customer' title='Delete Customer' ><span class='glyphicon glyphicon-trash'></span></a>";
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
        $customer = RoleUser::find($id);
        if (!$customer) {
            return Redirect::to("/admin/customer/")->with('error', trans('adminmsg.CUSTOMER_NOT_EXIST'));
        }
        return $this->_update($customer);
    }

    /**
     * Create/Update controller
     * @param $customer = customer object
     */
    private function _update($customer = null) {
        if ($customer === null) {
            $customer = new RoleUser;
        }
        
        $customerRoleDetail = Role::find(Config::get('constant.CUSTOMER'));
        $users = Common::getUserWithoutGivenRole($customerRoleDetail);
        $customerPicPath = $this->customerThumbImageUploadPath;
        $customerProofFrontPath = $this->customerProofFrontThumbImageUploadPath;
        $customerProofBackPath = $this->customerProofBackThumbImageUploadPath;

        return view('admin.customers.add', compact('customer', 'users', 'customerPicPath', 'customerProofFrontPath', 'customerProofBackPath'));
    }

    /**
     * @description: save user data
     * @return type
     */
    public function set(Request $request) {

        try {
            
            $rule = [
                'nominee_name' => 'required|max:100|regex:/^[a-zA-Z ]*$/',
                'nominee_address' => 'required|max:500'
            ];

            if(!$request->id || empty($request->id) || $request->id == 0 ) {
                $rule['user_id'] = 'required';
                $rule['nominee_photo'] = 'required|mimes:png|jpeg|jpg|bmp|max:10240';
                $rule['nominee_id_proof_front'] = 'required|mimes:png|jpeg|jpg|bmp|max:10240';
            } else {
                if($request->hidden_customer_pic == '') {
                    $rule['nominee_photo'] = 'required|mimes:png|jpeg|jpg|bmp|max:10240';
                }
                if($request->hidden_proof_front_pic == '') {
                    $rule['nominee_id_proof_front'] = 'required|mimes:png|jpeg|jpg|bmp|max:10240';
                }
            }
            
            $this->validate(request(), $rule);
            
            DB::beginTransaction();
            $customer = RoleUser::find($request->id);

            $postData = $request->only('nominee_name', 'nominee_address');

            $postData['nominee_photo'] = $request->hidden_customer_pic;
            $postData['nominee_id_proof_front'] = $request->hidden_proof_front_pic;
            $postData['nominee_id_proof_back'] = $request->hidden_proof_back_pic;

            // Upload User Image
            if (!empty($request->file('nominee_photo')) && $request->file('nominee_photo')->isValid()) {
                $params = [
                    'originalPath' => public_path($this->customerOriginalImageUploadPath),
                    'thumbPath' => public_path($this->customerThumbImageUploadPath),
                    'thumbHeight' => public_path($this->customerThumbImageHeight),
                    'thumbWidth' => public_path($this->customerThumbImageWidth),
                    'previousImage' => $request->hidden_customer_pic
                ];
                $nomineePic = ImageUpload::uploadWithThumbImage($request->file('nominee_photo'), $params);
                if ($nomineePic === FALSE) {
                    DB::rollback();
                    return Redirect::back()->withInput($request->all())->withErrors([
                                trans('adminmsg.NOMINEE_IMAGE_UPLOAD_ERROR_MSG')
                    ]);
                }
                $postData['nominee_photo'] = $nomineePic['imageName'];
            }

            // Upload Nominee front image
            if (!empty($request->file('nominee_id_proof_front')) && $request->file('nominee_id_proof_front')->isValid()) {
                $params = [
                    'originalPath' => public_path($this->customerProofFrontOriginalImageUploadPath),
                    'thumbPath' => public_path($this->customerProofFrontThumbImageUploadPath),
                    'thumbHeight' => public_path($this->customerProofFrontThumbImageHeight),
                    'thumbWidth' => public_path($this->customerProofFrontThumbImageWidth),
                    'previousImage' => $request->hidden_proof_front_pic
                ];
                $nomineeIDFrontPic = ImageUpload::uploadWithThumbImage($request->file('nominee_id_proof_front'), $params);
                if ($nomineeIDFrontPic === FALSE) {
                    DB::rollback();
                    return Redirect::back()->withInput($request->all())->withErrors([
                                trans('adminmsg.NOMINEE_ID_FRONT_IMAGE_UPLOAD_ERROR_MSG')
                    ]);
                }
                $postData['nominee_id_proof_front'] = $nomineeIDFrontPic['imageName'];
            }

            // Upload Nominee back image
            if (!empty($request->file('nominee_id_proof_back')) && $request->file('nominee_id_proof_back')->isValid()) {
                $params = [
                    'originalPath' => public_path($this->customerProofBackOriginalImageUploadPath),
                    'thumbPath' => public_path($this->customerProofBackThumbImageUploadPath),
                    'thumbHeight' => public_path($this->customerProofBackThumbImageHeight),
                    'thumbWidth' => public_path($this->customerProofBackThumbImageWidth),
                    'previousImage' => $request->hidden_proof_back_pic
                ];
                $nomineeIDBackPic = ImageUpload::uploadWithThumbImage($request->file('nominee_id_proof_back'), $params);
                if ($nomineeIDBackPic === FALSE) {
                    DB::rollback();
                    return Redirect::back()->withInput($request->all())->withErrors([
                                trans('adminmsg.NOMINEE_ID_BACK_IMAGE_UPLOAD_ERROR_MSG')
                    ]);
                }
                $postData['nominee_id_proof_back'] = $nomineeIDBackPic['imageName'];
            }
            
            if (isset($request->id) && $request->id > 0) {
                $customer->update(array_filter($postData));
                $customer->save();
                DB::commit();
                return Redirect::to("/admin/customer/")->with('success', trans('adminmsg.CUSTOMER_DETAIL_UPDATED_SUCCESS_MSG'));
            } else {
                $userData = User::find($request->user_id);
                if($userData == null) {
                    DB::rollback();
                    return Redirect::back()->withInput($request->all())->withErrors([
                                trans('adminmsg.USER_NOT_EXIST')
                    ]);
                }
                if($userData && $userData->deleted != Config::get('constant.ACTIVE_FLAG')) {
                    DB::rollback();
                    return Redirect::back()->withInput($request->all())->withErrors([
                                trans('adminmsg.USER_INACTIVE_DELETE')
                    ]);
                }

                // Assign role
                $userData->roles()->attach(Config::get('constant.CUSTOMER'), $postData);
                DB::commit();
                return Redirect::to("/admin/customer/")->with('success', trans('adminmsg.CUSTOMER_DETAIL_ADDED_SUCCESS_MSG'));
            }
        } catch (Exception $e) {
            DB::rollback();
            return Redirect::to("/admin/customer/")->with('error', trans('adminmsg.COMMON_ERROR_MSG'));
        }
    }

}
