<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTasksTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('user_tasks', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('client_id')->unsigned();
      $table->integer('project_id')->unsigned();
      $table->integer('timer')->nullable();
      $table->timestamp('started_at')->nullable();
      $table->timestamp('timer_date')->nullable();
      $table->boolean('is_running')->default(false);
      $table->timestamps();
      // Add new columns
      $table->integer('user_id')->unsigned()->after('id');
      $table->integer('task_id')->unsigned()->after('project_id');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('user_tasks');
  }
}
