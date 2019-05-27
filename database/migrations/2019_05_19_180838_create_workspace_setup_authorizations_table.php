<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkspaceSetupAuthorizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workspace_setup_authorizations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('admin_id')->nullable();
            $table->unsignedInteger('workspace_id')->nullable();
            $table->unsignedInteger('members_invited')->nullable();
            $table->unsignedInteger('members_limit');
            $table->string('subscription_id');
            $table->string('email');
            $table->string('customer');
            $table->string('code');
            $table->string('plan_id');
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
        Schema::dropIfExists('workspace_setup_authorizations');
    }
}
