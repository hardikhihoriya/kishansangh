<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class RoleTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        try {
            \DB::beginTransaction();
            \DB::table('roles')->truncate();

            \DB::table('roles')->insert(array(
                0 =>
                array(
                    'name' => 'superadmin',
                    'description' => 'A Super Admin',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ),
                1 =>
                array(
                    'name' => 'admin',
                    'description' => 'An Admin User',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ),
                2 =>
                array(
                    'name' => 'agent',
                    'description' => 'An Agent User',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ),
                3 =>
                array(
                    'name' => 'vendor',
                    'description' => 'A Vendor User',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ),
                4 =>
                array(
                    'name' => 'customer',
                    'description' => 'A Customer User',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ),
                5 =>
                array(
                    'name' => 'landowner',
                    'description' => 'A Land Owner',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ),
                6 =>
                array(
                    'name' => 'landworker',
                    'description' => 'A Land Worker',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ),
                7 =>
                array(
                    'name' => 'teammember',
                    'description' => 'A Team Member',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ),
                8 =>
                array(
                    'name' => 'officestaff',
                    'description' => 'An Office Staff',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                )
            ));
            \DB::commit();
        } catch (Exception $e) {
            \DB::rollback();
        }
    }

}
