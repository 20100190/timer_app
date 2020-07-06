
    <?php
        use Illuminate\Support\Facades\Schema;
        use Illuminate\Database\Schema\Blueprint;
        use Illuminate\Database\Migrations\Migration;
        
        class CreateContactPersonTable extends Migration
        {
            /**
             * Run the migrations.
             *
             * @return void
             */
            public function up()
            {
                Schema::create("contact person", function (Blueprint $table) {

						$table->increments('id')->unsigned();
						$table->integer('client_id')->nullable()->unsigned();
						$table->string('person',50)->nullable();
						$table->string('person_jp',50)->nullable();
						$table->string('person_title',20)->nullable();
						$table->string('tel',20)->nullable();
						$table->string('mobile_phone',20)->nullable();
						$table->string('fax',20)->nullable();
						$table->string('email',20)->nullable();
						$table->timestamps();
						$table->softDeletes();
						//$table->foreign("client_id")->references("id")->on("client");



						// ----------------------------------------------------
						// -- SELECT [contact person]--
						// ----------------------------------------------------
						// $query = DB::table("contact person")
						// ->leftJoin("client","client.id", "=", "contact person.client_id")
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
                Schema::dropIfExists("contact person");
            }
        }
    