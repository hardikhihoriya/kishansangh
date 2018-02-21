<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopMarketingTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('shop_marketing')) {
            Schema::create('shop_marketing', function (Blueprint $table) {
                $table->increments('id');
                $table->string('shop_marketing_name', 150);
                $table->integer('member')->nullable();
                $table->text('description')->nullable();
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
        Schema::dropIfExists('shop_marketing');
    }

}
