
    <?php
        use Illuminate\Support\Facades\Schema;
        use Illuminate\Database\Schema\Blueprint;
        use Illuminate\Database\Migrations\Migration;
        
        class CreateClientTable extends Migration
        {
            /**
             * Run the migrations.
             *
             * @return void
             */
            public function up()
            {
                Schema::create("client", function (Blueprint $table) {

						$table->string('id',3);
						$table->string('name',200)->nullable();
						$table->string('fye',5)->nullable();
						$table->string('vic_status',200)->nullable();
						$table->string('group_companies',200)->nullable(); //client - id
						$table->string('website',300)->nullable();
						$table->string('address_us',300)->nullable();
						$table->string('address_jp',300)->nullable();
						$table->string('mailing_address',100)->nullable();
						$table->string('tel1',50)->nullable();
						$table->string('tel2',50)->nullable();
						$table->string('tel3',50)->nullable();						
						$table->string('fax',50)->nullable();
						$table->string('federal_id',20)->nullable();
						$table->string('state_id',20)->nullable();
						$table->string('edd_id',20)->nullable();
						$table->string('note',300)->nullable();
						$table->integer('pic')->nullable();
						$table->integer('nature_of_business')->nullable();
						$table->date('incorporation_date')->nullable();
						$table->string('incorporation_state',50)->nullable();
						$table->date('business_started')->nullable();
						$table->timestamps();
						$table->softDeletes();



						// ----------------------------------------------------
						// -- SELECT [client]--
						// ----------------------------------------------------
						// $query = DB::table("client")
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
                Schema::dropIfExists("client");
            }
        }
    