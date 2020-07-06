
    <?php
        use Illuminate\Support\Facades\Schema;
        use Illuminate\Database\Schema\Blueprint;
        use Illuminate\Database\Migrations\Migration;
        
        class CreatePhaseItemsTable extends Migration
        {
            /**
             * Run the migrations.
             *
             * @return void
             */
            public function up()
            {
                Schema::create("phase items", function (Blueprint $table) {

						$table->increments('id')->unsigned();
						$table->integer('phase_id')->nullable()->unsigned();
						$table->string('name',50)->nullable();
						$table->integer('is_standard')->nullable();
						$table->integer('order')->nullable();
						$table->string('description',200)->nullable();
						$table->timestamps();
						$table->softDeletes();
						//$table->foreign("phase_id")->references("id")->on("phase");



						// ----------------------------------------------------
						// -- SELECT [phase items]--
						// ----------------------------------------------------
						// $query = DB::table("phase items")
						// ->leftJoin("phase","phase.id", "=", "phase items.phase_id")
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
                Schema::dropIfExists("phase items");
            }
        }
    