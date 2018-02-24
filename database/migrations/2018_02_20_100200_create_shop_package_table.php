<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopPackageTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('shop_package')) {
            Schema::create('shop_package', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('shop_marketing_id')->unsigned()->nullable();
                $table->string('package_name')->nullable();
                $table->float('price', 8, 2)->nullable()->comment('Per Year');
                $table->float('boosting_point', 8, 2)->nullable();
                $table->text('package_description')->nullable();
                $table->integer('per_day_SMS')->nullable();
                $table->enum('status', ['active', 'inactive', 'deleted'])->default('active');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('shop_package');
    }

}
