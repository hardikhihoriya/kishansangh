<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopTypeTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('shop_type')) {
            Schema::create('shop_type', function (Blueprint $table) {
                $table->increments('id');
                $table->string('shop_type_name', 150);
                $table->string('shop_type_icon')->nullable();
                $table->text('shop_type_detail')->nullable();
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
        Schema::dropIfExists('shop_type');
    }

}
