<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyConstraintsToTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->change();
            $table->unsignedBigInteger('workspace_id')->change();
            $table->unsignedBigInteger('user_responsible')->change();

            $table->foreign('created_by')
                ->references('id')
                ->on('users');
            
            $table->foreign('workspace_id')
                ->references('id')
                ->on('workspaces')
                ->onDelete('cascade');
            
            $table->foreign('user_responsible')
                ->references('id')
                ->on('users');
        });
    }
}
