
    <?php
        use Illuminate\Support\Facades\Schema;
        use Illuminate\Database\Schema\Blueprint;
        use Illuminate\Database\Migrations\Migration;
        
        class CreatePhaseTable extends Migration
        {
            /**
             * Run the migrations.
             *
             * @return void
             */
            public function up()
            {
                Schema::create("phase", function (Blueprint $table) {

						$table->increments('id');
						$table->string('project_type',5)->nullable();
						$table->string('name',50)->nullable();
						$table->string('color',10)->nullable();
						$table->integer('order')->nullable();
						$table->string('description',200)->nullable();
						$table->timestamps();
						$table->softDeletes();



						// ----------------------------------------------------
						// -- SELECT [phase]--
						// ----------------------------------------------------
						// $query = DB::table("phase")
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
                Schema::dropIfExists("phase");
            }
        }
    