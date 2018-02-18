<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomerDetailColumnInUserRoleTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('role_user', function (Blueprint $table) {
            $table->string('nominee_name', 150)->nullable()->after('status');
            $table->string('nominee_photo')->nullable()->after('nominee_name');
            $table->string('nominee_id_proof_front')->nullable()->after('nominee_photo');
            $table->string('nominee_id_proof_back')->nullable()->after('nominee_id_proof_front');
            $table->text('nominee_address')->nullable()->after('nominee_id_proof_back');
            $table->double('customer_wallet', 15, 2)->after('nominee_address')->default(0.00);
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
                'nominee_name',
                'nominee_photo',
                'nominee_id_proof_front',
                'nominee_id_proof_back',
                'nominee_address',
                'customer_wallet'
            ]);
        });
    }

}
