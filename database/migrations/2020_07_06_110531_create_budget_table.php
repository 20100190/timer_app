
    <?php
        use Illuminate\Support\Facades\Schema;
        use Illuminate\Database\Schema\Blueprint;
        use Illuminate\Database\Migrations\Migration;
        
        class CreateBudgetTable extends Migration
        {
            /**
             * Run the migrations.
             *
             * @return void
             */
            public function up()
            {
                Schema::create("budget", function (Blueprint $table) {

						$table->integer('assign_id')->nullable();
						$table->integer('year')->nullable();
						$table->integer('month')->nullable();
						$table->integer('day')->nullable(); //月曜日の日付
						$table->double('working_days')->nullable();
						$table->integer('ymd')->nullable();
						$table->timestamps();
						$table->softDeletes();



						// ----------------------------------------------------
						// -- SELECT [budget]--
						// ----------------------------------------------------
						// $query = DB::table("budget")
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
                Schema::dropIfExists("budget");
            }
        }
    