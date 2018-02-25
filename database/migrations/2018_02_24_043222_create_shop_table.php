<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('shop')) {
            Schema::create('shop', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->unsigned()->nullable();
                $table->integer('shop_type_id')->unsigned()->nullable();
                $table->integer('shop_package_id')->unsigned()->nullable();
                $table->string('shop_name', 100)->nullable();
                $table->string('shop_registration_no')->nullable();
                $table->string('shop_email')->nullable();
                $table->string('shop_web_url')->nullable();
                $table->string('shop_phone_no', 15)->nullable();
                $table->string('shop_logo')->nullable();
                $table->date('shop_anniversary_date')->nullable();
                $table->text('address')->nullable();
                $table->string('zipcode', 6)->nullable();
                $table->string('lat')->nullable();
                $table->string('lng')->nullable();
                $table->enum('status', ['active', 'inactive', 'deleted'])->default('active');
                $table->timestamps();
                
                $table->foreign('user_id')->references('id')->on('users');
                $table->foreign('shop_type_id')->references('id')->on('shop_type');
                $table->foreign('shop_package_id')->references('id')->on('shop_package');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('shop', function(Blueprint $table) {
            $table->dropForeign('shop_user_id_foreign');
            $table->dropForeign('shop_shop_type_id_foreign');
            $table->dropForeign('shop_shop_package_id_foreign');
        });
        Schema::dropIfExists('shop');
    }

}
