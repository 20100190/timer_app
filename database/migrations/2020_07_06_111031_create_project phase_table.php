
    <?php
        use Illuminate\Support\Facades\Schema;
        use Illuminate\Database\Schema\Blueprint;
        use Illuminate\Database\Migrations\Migration;
        
        class CreateProjectPhaseTable extends Migration
        {
            /**
             * Run the migrations.
             *
             * @return void
             */
            public function up()
            {
                Schema::create("project phase", function (Blueprint $table) {

						$table->increments('id')->unsigned();
						$table->integer('project_id')->nullable()->unsigned();
						$table->integer('phase_id')->nullable()->unsigned();
						$table->integer('year')->nullable();
						$table->integer('month')->nullable();
						$table->integer('day')->nullable();
						$table->timestamps();
						$table->softDeletes();
						//$table->foreign("project_id")->references("id")->on("project");
						//$table->foreign("phase_id")->references("id")->on("phase");



						// ----------------------------------------------------
						// -- SELECT [project phase]--
						// ----------------------------------------------------
						// $query = DB::table("project phase")
						// ->leftJoin("project","project.id", "=", "project phase.project_id")
						// ->leftJoin("phase","phase.id", "=", "project phase.phase_id")
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
                Schema::dropIfExists("project phase");
            }
        }
    