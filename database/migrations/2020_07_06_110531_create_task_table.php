
    <?php
        use Illuminate\Support\Facades\Schema;
        use Illuminate\Database\Schema\Blueprint;
        use Illuminate\Database\Migrations\Migration;
        
        class CreateTaskTable extends Migration
        {
            /**
             * Run the migrations.
             *
             * @return void
             */
            public function up()
            {
                Schema::create("task", function (Blueprint $table) {

						$table->increments('id');
						$table->string('project_type',5)->nullable();
						$table->integer('name')->nullable();
						$table->integer('is_standard')->nullable();
						$table->timestamps();
						$table->softDeletes();



						// ----------------------------------------------------
						// -- SELECT [task]--
						// ----------------------------------------------------
						// $query = DB::table("task")
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
                Schema::dropIfExists("task");
            }
        }
    