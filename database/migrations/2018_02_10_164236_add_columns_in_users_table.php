<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name', 100)->nullable()->after('password');
            $table->string('middle_name', 100)->nullable()->after('first_name');
            $table->string('last_name', 100)->nullable()->after('middle_name');
            $table->string('registration_no')->nullable()->after('last_name');
            $table->string('phone_no', 10)->nullable()->after('registration_no');
            $table->date('birth_date')->nullable()->after('phone_no');
            $table->enum('gender', ['male', 'female', 'non-binary'])->default('male')->after('birth_date');
            $table->text('address')->nullable()->after('gender');
            $table->string('zipcode', 6)->nullable()->after('address');
            $table->enum('married', ['yes', 'no'])->default('no')->after('zipcode');
            $table->date('marriage_anniversary_date')->nullable()->after('married');
            $table->string('user_pic')->nullable()->after('marriage_anniversary_date');
            $table->string('signature')->nullable()->after('user_pic');
            $table->string('bank_name')->nullable()->after('signature');
            $table->string('account_no')->nullable()->after('bank_name');
            $table->string('ifsc_code')->nullable()->after('account_no');
            $table->enum('notification_status', ['on', 'off'])->default('on')->after('ifsc_code');
            $table->enum('deleted', ['active', 'inactive', 'deleted'])->default('active')->after('notification_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'middle_name',
                'last_name',
                'registration_no',
//                'phone_no',
                'birth_date',
                'gender',
                'address',
//                'zipcode',
                'married',
                'marriage_anniversary_date',
                'user_pic',
                'signature',
                'bank_name',
                'account_no',
                'ifsc_code',
                'notification_status',
                'deleted'
            ]);
        });
    }
}
