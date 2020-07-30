
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create("staff", function (Blueprint $table) {

            $table->increments('id');
            $table->string('employee_no', 4)->nullable();
            $table->string('first_name', 20)->nullable();
            $table->string('last_name', 20)->nullable();
            $table->string('initial', 10)->nullable();
            $table->string('department', 100)->nullable();
            $table->string('title', 50)->nullable();
            $table->string('billing_title', 50)->nullable();
            $table->integer('rate')->nullable();
            $table->string('extension')->nullable();
            $table->string('email', 50)->nullable();
            $table->string('cell_phone', 50)->nullable();
            $table->string('status', 20)->nullable();
            $table->string('default_role', 50)->nullable();


            // ----------------------------------------------------
            // -- SELECT [staff]--
            // ----------------------------------------------------
            // $query = DB::table("staff")
            // ->get();
            // dd($query); //For checking
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists("staff");
    }

}
