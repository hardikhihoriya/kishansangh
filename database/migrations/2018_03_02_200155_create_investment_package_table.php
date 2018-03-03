<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvestmentPackageTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('investment_package')) {
            Schema::create('investment_package', function (Blueprint $table) {
                $table->increments('id');
                $table->string('investment_package_name', 100)->nullable();
                $table->string('investment_package_year', 4)->nullable();
                $table->integer('investment_package_size')->nullable();
                $table->integer('investment_package_duration')->nullable()->comment('In year');
                $table->text('investment_package_description')->nullable();
                $table->double('investment_package_price', 15, 2)->default(0.00);
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
        Schema::dropIfExists('investment_package');
    }

}
