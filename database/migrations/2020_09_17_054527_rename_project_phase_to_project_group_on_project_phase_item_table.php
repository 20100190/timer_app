<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameProjectPhaseToProjectGroupOnProjectPhaseItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project phase item', function (Blueprint $table) {
            $table->dropColumn('project_phase_id');
            $table->integer('phase_item_id')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project phase item', function (Blueprint $table) {
            $table->dropColumn('phase_group_id');
            $table->integer('project_phase_id')->after('id');            
        });
    }
}
