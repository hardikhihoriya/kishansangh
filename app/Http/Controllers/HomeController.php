<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Common;
use App\Role;
use Config;

class HomeController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $totalRegistration = Common::getTotalRegistrationCount();
        $customerRole = Role::find(Config::get('constant.CUSTOMER'));
        $vendorRole = Role::find(Config::get('constant.VENDOR'));
        $customer = Common::getTotalCount($customerRole);
        $vendor = Common::getTotalCount($vendorRole);
        return view('admin.dashboard', compact('totalRegistration', 'customer', 'vendor'));
    }

}
