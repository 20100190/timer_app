<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateIntToDateOnProjectPhaseItemTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('project phase item', function (Blueprint $table) {
            $table->dropColumn('planned_review2');            
            $table->dropColumn('review_sign_off2');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('project phase item', function (Blueprint $table) {
            //$table->integer('planned_review2')->nullable()->change();
            //$table->integer('review_sign_off2')->nullable()->change();
        });
    }

}
