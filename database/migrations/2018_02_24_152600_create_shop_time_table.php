<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopTimeTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('shop_time')) {
            Schema::create('shop_time', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('shop_id')->unsigned()->nullable();
                $table->enum('day', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])->nullable();
                $table->string('start_time')->nullable();
                $table->string('close_time')->nullable();
                $table->timestamps();
                
                $table->foreign('shop_id')->references('id')->on('shop');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('shop_time', function(Blueprint $table) {
            $table->dropForeign('shop_time_shop_id_foreign');
        });
        
        Schema::dropIfExists('shop_time');
    }

}
