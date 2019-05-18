<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('stripe_id')->nullable();
            $table->unsignedInteger('bundle_id');
            $table->string('bundle');
            $table->string('customer');
            $table->string('email');
            $table->string('billing');
            $table->string('plan_id');
            $table->unsignedInteger('amount');
            $table->string('status');
            $table->date('start_date');
            $table->date('expires_at');
            $table->date('cancelled_at')->nullable();
            $table->date('ended_at')->nullable();
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
        Schema::dropIfExists('subscriptions');
    }
}
