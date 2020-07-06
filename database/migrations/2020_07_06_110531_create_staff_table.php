
    <?php
        use Illuminate\Support\Facades\Schema;
        use Illuminate\Database\Schema\Blueprint;
        use Illuminate\Database\Migrations\Migration;
        
        class CreateStaffTable extends Migration
        {
            /**
             * Run the migrations.
             *
             * @return void
             */
            public function up()
            {
                Schema::create("staff", function (Blueprint $table) {

						$table->increments('id');
						$table->string('employee_no',3)->nullable();
						$table->string('first_name',20)->nullable();
						$table->string('last_name',20)->nullable();
						$table->string('initial',3)->nullable();
						$table->string('department',100)->nullable();
						$table->string('title',30)->nullable();
						$table->string('billing_title',20)->nullable();
						$table->integer('rate')->nullable();
						$table->integer('extension')->nullable();
						$table->string('email',50)->nullable();
						$table->string('cell_phone',15)->nullable();
						$table->string('status',10)->nullable();
						$table->string('default_role',20)->nullable();
						$table->timestamps();
						$table->softDeletes();



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
            public function down()
            {
                Schema::dropIfExists("staff");
            }
        }
    