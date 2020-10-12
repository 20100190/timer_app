
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEngagementTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create("engagement", function (Blueprint $table) {

            $table->increments('id');            
            $table->integer('project_id');
            $table->integer('no')->nullable();
            $table->string('type',50)->nullable();
            $table->integer('col1')->nullable();
            $table->integer('col2')->nullable();
            $table->integer('col3')->nullable();
            $table->integer('col4')->nullable();
            $table->integer('col5')->nullable();
            $table->integer('col6')->nullable();
            $table->integer('col7')->nullable();
            $table->integer('col8')->nullable();
            $table->integer('col9')->nullable();
            $table->integer('col10')->nullable();
            $table->integer('col11')->nullable();
            $table->integer('col12')->nullable();
            $table->string('start_month',3)->nullable();
            $table->integer('start_year')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists("week");
    }

}
