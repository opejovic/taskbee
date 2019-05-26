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
            $table->unsignedInteger('members_limit')->nullable();
            $table->unsignedInteger('subscription_id')->nullable();
            $table->string('email');
            $table->string('user_role');
            $table->string('code');
            $table->string('plan_id')->nullable();
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
