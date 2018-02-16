<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Config;
use App\Helpers\ImageUpload;
use App\User;
use App\Role;
use Redirect;
use File;
use Response;
use DB;

class UserController extends Controller {
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->userOriginalImageUploadPath = Config::get('constant.USER_ORIGINAL_IMAGE_UPLOAD_PATH');
        $this->userThumbImageUploadPath = Config::get('constant.USER_THUMB_IMAGE_UPLOAD_PATH');
        $this->userThumbImageHeight = Config::get('constant.USER_THUMB_IMAGE_HEIGHT');
        $this->userThumbImageWidth = Config::get('constant.USER_THUMB_IMAGE_WIDTH');

        $this->userSignOriginalImageUploadPath = Config::get('constant.USER_SIGN_ORIGINAL_IMAGE_UPLOAD_PATH');
    }

    public function index() {
        return view('admin.users.list');
    }
    
    /**
     * [listAjax List Users]
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
                    foreach ($idArray as $_idArray) {
                        $user = User::find($_idArray);
                        $user->fill(array_filter(['deleted' => Config::get('constant.DELETED_FLAG')]));
                        $user->save();
                    }
                    $records["customMessage"] = trans('adminmsg.DELETE_USER');
            }
        }
        
        $columns = array( 
            0 => 'registration_no', 
            1 => 'first_name',
            2 => 'email',
            3 => 'phone',
            4 => 'birth_date',
            5 => 'gender'
        );
        
        $order = $request->order;
        $search = $request->search;
        $records["data"] = array();
        
        //getting records for the users table
        $iTotalRecords = User::where('deleted', '<>', Config::get('constant.DELETED_FLAG'))->count();
        $iTotalFiltered = $iTotalRecords;
        $iDisplayLength = intval($request->length) <= 0 ? $iTotalRecords : intval($request->length);
        $iDisplayStart = intval($request->start);
        $sEcho = intval($request->draw);
        
        $records["data"] = User::where('deleted', '<>', Config::get('constant.DELETED_FLAG'));
        
        if (!empty($search['value'])) {
            $val = $search['value'];
            $records["data"]->where(function($query) use ($val) {
                $query->SearchName($val)
                    ->SearchRegistrationNo($val)
                    ->SearchEmail($val)
                    ->SearchPhone($val)
                    ->SearchBirthDate($val)
                    ->SearchGender($val);
            });
            
            // No of record after filtering
            $iTotalFiltered = $records["data"]->where(function($query) use ($val) {
                                $query->SearchName($val)
                                    ->SearchRegistrationNo($val)
                                    ->SearchEmail($val)
                                    ->SearchPhone($val)
                                    ->SearchBirthDate($val)
                                    ->SearchGender($val);
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
                $edit =  route('user.edit', $_records->id);

                $records["data"][$key]['name'] = $_records->first_name. (!empty($_records->middle_name) ? ' '.$_records->middle_name : ''). (!empty($_records->last_name) ? ' '.$_records->last_name : '');
                $records["data"][$key]['user_pic'] = ($_records->user_pic != '' && File::exists(public_path($this->userThumbImageUploadPath . $_records->user_pic)) ? '<img src="'.url($this->userThumbImageUploadPath.$_records->user_pic).'" alt="{{$_records->user_pic}}"  height="50" width="50">' : '<img src="'.asset('/images/default.png').'" class="user-image" alt="Default Image" height="50" width="50">');
                $records["data"][$key]['action'] = "&emsp;<a href='{$edit}' title='Edit User' ><span class='glyphicon glyphicon-edit'></span></a>
                                                    &emsp;<a href='javascript:;' data-id='". $_records->id ."' class='btn-delete-user' title='Delete User' ><span class='glyphicon glyphicon-trash'></span></a>";
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
        $user = User::find($id);
        
        if(!$user) {
            return Redirect::to("/admin/sanghusers/")->with('error', trans('adminmsg.USER_NOT_EXIST'));
        }

        return $this->_update($user);
    }

    /**
     * Create/Update controller
     * @param $user = user object
     */
    private function _update($user = null) {
        if ($user === null) {
            $user = new User;
        }
        
        $roles = Role::all();
        $userPicPath = $this->userThumbImageUploadPath;
        $userSignaturePath = $this->userSignOriginalImageUploadPath;
        
        return view('admin.users.add', compact('user', 'roles', 'userPicPath', 'userSignaturePath'));
    }

    /**
     * @description: save user data
     * @return type
     */
    public function set(Request $request) {
        
        try {
            DB::beginTransaction();
            $user = User::find($request->id);

            $postData = $request->only('first_name', 'middle_name', 'last_name', 'email', 'phone_no', 'birth_date', 'gender', 'address', 'zipcode', 'married', 'marriage_anniversary_date', 'bank_name', 'account_no', 'ifsc_code');

            $postData['user_pic'] = $request->hidden_user_pic;
            $postData['signature'] = $request->hidden_user_sign;

            // Upload User Image
            if (!empty($request->file('user_pic')) && $request->file('user_pic')->isValid()) {
                $params = [
                    'originalPath' => public_path($this->userOriginalImageUploadPath),
                    'thumbPath' => public_path($this->userThumbImageUploadPath),
                    'thumbHeight' => public_path($this->userThumbImageHeight),
                    'thumbWidth' => public_path($this->userThumbImageWidth),
                    'previousImage' => $request->hidden_user_pic
                ];
                $userPic = ImageUpload::uploadWithThumbImage($request->file('user_pic'), $params);
                if($userPic === FALSE) {
                    DB::rollback();
                    return Redirect::back()->withInput($request->all())->withErrors([
                        trans('adminmsg.USER_IMAGE_UPLOAD_ERROR_MSG')
                    ]);
                }
                $postData['user_pic'] = $userPic['imageName'];
            }

            // Upload Signature
            if (!empty($request->file('signature')) && $request->file('signature')->isValid()) {
                $params = [
                    'originalPath' => public_path($this->userSignOriginalImageUploadPath),
                    'previousImage' => $request->hidden_user_sign
                ];
                $signature = ImageUpload::uploadImage($request->file('signature'), $params);
                if($signature === FALSE) {
                    DB::rollback();
                    return Redirect::back()->withInput($request->all())->withErrors([
                        trans('adminmsg.USER_SIGNATURE_UPLOAD_ERROR_MSG')
                    ]);
                }
                $postData['signature'] = $signature['imageName'];
            }

            if (isset($request->id) && $request->id > 0) {
                $postData['marriage_anniversary_date'] = ($postData['married'] == 'no' ? null : $postData['marriage_anniversary_date']);
                $user->update(array_filter($postData));
                $user->save();
                DB::commit();
                return Redirect::to("/admin/sanghusers/")->with('success', trans('adminmsg.USER_UPDATED_SUCCESS_MSG'));
            } else {
                $userModel = new User();
                $postData['registration_no'] = $userModel->generateRegistrationNo($postData);
                $postData['password'] = bcrypt(str_random(8));
                $postData['marriage_anniversary_date'] = ($postData['married'] == 'no' ? null : $postData['marriage_anniversary_date']);
                
                $user = new User(array_filter($postData));
                $user->save();
                // Assign role
                foreach ($request->roles as $_roles) {
                    $user->roles()->attach($_roles);
                }
                DB::commit();
                return Redirect::to("/admin/sanghusers/")->with('success', trans('adminmsg.USER_CREATED_SUCCESS_MSG'));  
            }
        } catch (Exception $e) {
            DB::rollback();
            return Redirect::to("/admin/sanghusers/")->with('error', trans('adminmsg.COMMON_ERROR_MSG'));  
        }
    }
}
