
    <?php
        use Illuminate\Support\Facades\Schema;
        use Illuminate\Database\Schema\Blueprint;
        use Illuminate\Database\Migrations\Migration;
        
        class CreateProjectPhaseItemTable extends Migration
        {
            /**
             * Run the migrations.
             *
             * @return void
             */
            public function up()
            {
                Schema::create("project phase item", function (Blueprint $table) {

						$table->increments('id')->unsigned();
						$table->integer('project_phase_id')->nullable()->unsigned();
						$table->string('memo',200)->nullable();
						$table->date('due_date')->nullable();
						$table->integer('preparer')->nullable();
						$table->date('planed_prep')->nullable();
						$table->date('prep_sign_off')->nullable();
						$table->integer('reviewer')->nullable();
						$table->date('planned_review')->nullable();
						$table->date('review_sign_off')->nullable();
						$table->integer('reviewer2')->nullable();
						$table->integer('planned_review2')->nullable();
						$table->integer('review_sign_off2')->nullable();
						$table->timestamps();
						$table->softDeletes();
						//$table->foreign("project_phase_id")->references("id")->on("project phase");



						// ----------------------------------------------------
						// -- SELECT [project phase item]--
						// ----------------------------------------------------
						// $query = DB::table("project phase item")
						// ->leftJoin("project phase","project phase.id", "=", "project phase item.project_phase_id")
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
                Schema::dropIfExists("project phase item");
            }
        }
    