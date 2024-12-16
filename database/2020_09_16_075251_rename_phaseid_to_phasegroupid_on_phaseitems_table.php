<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenamePhaseidToPhasegroupidOnPhaseitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('phase items', function (Blueprint $table) {
            $table->dropColumn('phase_id');
            $table->integer('phase_group_id')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('phase items', function (Blueprint $table) {
            //$table->renameColumn('phase_group_id', 'phase_id');
        });
    }
}
