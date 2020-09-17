<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnOnProjectPhaseItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project phase item', function (Blueprint $table) {              
            $table->date('planned_review2')->nullable()->after('reviewer2');
            $table->date('review_sign_off2')->nullable()->after('planned_review2');    
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
            $table->dropColumn('planned_review2');            
            $table->dropColumn('review_sign_off2');   
        });
    }
}
