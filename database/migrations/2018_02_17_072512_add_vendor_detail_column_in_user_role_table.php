<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVendorDetailColumnInUserRoleTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('role_user', function (Blueprint $table) {
            $table->string('vendor_name', 150)->nullable()->after('user_id');
            $table->text('vendor_address')->nullable()->after('vendor_name');
            $table->enum('status', ['active', 'inactive', 'deleted'])->default('active')->after('vendor_address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('role_user', function (Blueprint $table) {
            $table->dropColumn([
                'vendor_name',
                'vendor_address',
                'status'
            ]);
        });
    }

}
