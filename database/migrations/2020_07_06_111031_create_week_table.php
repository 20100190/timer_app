
    <?php
        use Illuminate\Support\Facades\Schema;
        use Illuminate\Database\Schema\Blueprint;
        use Illuminate\Database\Migrations\Migration;
        
        class CreateWeekTable extends Migration
        {
            /**
             * Run the migrations.
             *
             * @return void
             */
            public function up()
            {
                Schema::create("week", function (Blueprint $table) {

						$table->integer('year');
						$table->integer('month');
						$table->integer('week');
						$table->integer('day');
                                                $table->integer('operating_time');
						$table->timestamps();
						$table->softDeletes();
						//$table->foreign("id")->references("assign_id")->on("budget");
						//$table->foreign("project_id")->references("id")->on("project");
						//$table->foreign("staff_id")->references("id")->on("staff");



						// ----------------------------------------------------
						// -- SELECT [assign]--
						// ----------------------------------------------------
						// $query = DB::table("assign")
						// ->leftJoin("budget","budget.assign_id", "=", "assign.id")
						// ->leftJoin("project","project.id", "=", "assign.project_id")
						// ->leftJoin("staff","staff.id", "=", "assign.staff_id")
						// ->get();
						// dd($query); //For checking



                });
            }

            /**
             * Reverse the migrations.
             *
             * @return void
             */
            public function down()
            {
                Schema::dropIfExists("week");
            }
        }
    