<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyConstraintsToWorkspaceUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workspace_user', function (Blueprint $table) {
            $table->unsignedBigInteger('workspace_id')->change();
            $table->unsignedBigInteger('user_id')->change();

            $table->foreign('workspace_id')->references('id')->on('workspaces');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
}
