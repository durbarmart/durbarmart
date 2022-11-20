<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryBoysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    //     ['first_name', 'middle_name', 'last_name','email', 'phone_number','avatar',
    //  'dob', 'blood_group','commission','password', 'active_status', 'availability_status', 'address',
    //   'city', 'country_id', 'state_id', 'zip_code', 'vechile_name', 'owner_name', 'vechile_color',
    //    'vechile_registration_no', 'vechile_details','driving_license_no', 'vechile_rc_book_no','account_name',
    //     'account_number','gpay_number','bank_address','ifsc_code', 'branch_name'];
        Schema::create('delivery_boys', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('email')->uniqiue();
            $table->string('phone_number');
            $table->string('avatar')->nullable()->default('default.png');
            $table->string('dob')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('commission')->nullable();
            $table->string('password');
            $table->tinyInteger('active_status')->default(1)->nullable();
            $table->tinyInteger('availability_status')->default(1)->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->unsignedBigInteger('country_id');
            $table->unsignedBigInteger('state_id');
            $table->string('zip_code')->nullable();
            $table->string('vechile_name')->nullable();
            $table->string('owner_name')->nullable();
            $table->string('vechile_color')->nullable();
            $table->string('vechile_registration_no')->nullable();
            $table->longText('vechile_details')->nullable();
            $table->string('driving_license_no')->nullable();
            $table->string('vechile_rc_book_no')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('gpay_number')->nullable();
            $table->string('bank_address')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('branch_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_boys');
    }
}
