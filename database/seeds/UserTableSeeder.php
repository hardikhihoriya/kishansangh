<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$super_admin = Role::where('name', 'superadmin')->first();
    	$admin  = Role::where('name', 'admin')->first();        
    	$agent  = Role::where('name', 'agent')->first();        
    	$vendor  = Role::where('name', 'vendor')->first();        
    	$customer  = Role::where('name', 'customer')->first(); 

    	$super_admin_user = new User();
    	$admin_user = new User();
    	$agent_user = new User();
    	$vendor_user = new User();
    	$customer_user = new User();
	    //Add super admin with super admin role. We can also assign other roles also
	    $super_admin_user->name = 'Super Admin';
	    $super_admin_user->email = 'superadmin@gmail.com';
	    $super_admin_user->password = bcrypt('secret');
	    $super_admin_user->save();
	    $super_admin_user->roles()->attach($super_admin);
	    //Add admin with admin role. We can also assign other roles also
	    $admin_user->name = 'Admin User';
	    $admin_user->email = 'admin@gmail.com';
	    $admin_user->password = bcrypt('secret');
	    $admin_user->save();
	    $admin_user->roles()->attach($admin);
	    //Add agent with agent role. We can also assign other roles also
	    $agent_user->name = 'Agent User';
	    $agent_user->email = 'agent@gmail.com';
	    $agent_user->password = bcrypt('secret');
	    $agent_user->save();
	    $agent_user->roles()->attach($agent);
	    //Add vendor with vendor role. We can also assign other roles also
	    $vendor_user->name = 'Vendor User';
	    $vendor_user->email = 'vendor@gmail.com';
	    $vendor_user->password = bcrypt('secret');
	    $vendor_user->save();
	    $vendor_user->roles()->attach($vendor);
	    //Add customer with customer role. We can also assign other roles also
	    $customer_user->name = 'Customer User';
	    $customer_user->email = 'customer@gmail.com';
	    $customer_user->password = bcrypt('secret');
	    $customer_user->save();
	    $customer_user->roles()->attach($customer);
    }
}
