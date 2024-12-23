<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditUserTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_tasks', function (Blueprint $table) {
            // Add new columns
            $table->integer('user_id')->unsigned()->after('id');
            $table->integer('task_id')->unsigned()->after('project_id');

            // Remove unnecessary columns
            $table->dropColumn(['username', 'client_name', 'project_name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_tasks', function (Blueprint $table) {
            // Re-add removed columns
            $table->string('username')->after('id');
            $table->string('client_name')->after('client_id');
            $table->string('project_name')->after('project_id');

            // Remove newly added columns
            $table->dropColumn(['user_id', 'task_id']);
        });
    }
}
