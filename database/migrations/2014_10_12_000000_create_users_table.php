<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();

            $table->index(['email']);

            $table->timestamps();
        });

        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('code')->unique()->nullable();
            $table->string('latest_receipt')->unique()->nullable();
            $table->string('provider_name')->nullable();
            $table->timestamp('initiated_at')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->timestamp('renewed_at')->nullable();

            $table->timestamp('failed_to_renew_at')->nullable();
            $table->timestamp('subscription_valid_until')->nullable();

            $table->string('verification_key')->nullable();

            $table->boolean('is_in_billing_retry_period')->default(0);

            $table->timestamps();
        });

        Schema::create('user_subscription_receipts', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('subscription_id')->unsigned();
            $table->foreign('subscription_id')->references('id')->on('user_subscriptions')->onDelete('cascade');

            //$table->string('receipt_code')->unique()->nullable();
            $table->string('receipt_code')->nullable();

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
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('user_subscription_receipts');
        Schema::dropIfExists('user_subscriptions');
        Schema::dropIfExists('users');
    }
}
