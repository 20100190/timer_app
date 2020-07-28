
    <?php
        use Illuminate\Support\Facades\Schema;
        use Illuminate\Database\Schema\Blueprint;
        use Illuminate\Database\Migrations\Migration;
        
        class CreateProjectTaskTable extends Migration
        {
            /**
             * Run the migrations.
             *
             * @return void
             */
            public function up()
            {
                Schema::create("project task", function (Blueprint $table) {

						$table->increments('id')->unsigned();
						$table->integer('project_id')->nullable()->unsigned();
						$table->integer('task_id')->nullable()->unsigned();
                                                $table->integer('order_no')->nullable();
						$table->timestamps();
						$table->softDeletes();
						//$table->foreign("project_id")->references("id")->on("project");
						//$table->foreign("task_id")->references("id")->on("task");



						// ----------------------------------------------------
						// -- SELECT [project task]--
						// ----------------------------------------------------
						// $query = DB::table("project task")
						// ->leftJoin("project","project.id", "=", "project task.project_id")
						// ->leftJoin("task","task.id", "=", "project task.task_id")
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
                Schema::dropIfExists("project task");
            }
        }
    