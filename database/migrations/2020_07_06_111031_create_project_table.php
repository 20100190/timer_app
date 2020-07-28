
    <?php
        use Illuminate\Support\Facades\Schema;
        use Illuminate\Database\Schema\Blueprint;
        use Illuminate\Database\Migrations\Migration;
        
        class CreateProjectTable extends Migration
        {
            /**
             * Run the migrations.
             *
             * @return void
             */
            public function up()
            {
                Schema::create("project", function (Blueprint $table) {

						$table->increments('id');
						$table->integer('client_id')->nullable()->unsigned();
						$table->string('project_type',100)->nullable();
						$table->integer('project_year')->nullable();
						$table->string('project_name',200)->nullable();
						$table->integer('pic')->nullable()->unsigned(); //社員マスタ - id
						$table->date('start')->nullable();
						$table->date('end')->nullable();
						$table->string('billable',15)->nullable();
						$table->string('note',300)->nullable();
						$table->integer('engagement_fee_unit')->nullable();
						$table->integer('invoice_per_year')->nullable();
						$table->integer('adjustments')->nullable();
						$table->timestamps();
						$table->softDeletes();
						

                    //*********************************
                    // Foreign KEY [ Uncomment if you want to use!! ]
                    //*********************************
                        //$table->foreign("client_id")->references("id")->on("client");
						//$table->foreign("pic")->references("id")->on("staff");



						// ----------------------------------------------------
						// -- SELECT [project]--
						// ----------------------------------------------------
						// $query = DB::table("project")
						// ->leftJoin("client","client.id", "=", "project.client_id")
						// ->leftJoin("staff","staff.id", "=", "project.pic")
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
                Schema::dropIfExists("project");
            }
        }
    