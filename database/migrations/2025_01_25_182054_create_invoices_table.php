<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->integer('user_id')->unsigned();
            $table->integer('client_id')->unsigned();
            $table->integer('project_id')->unsigned();
            $table->string('type');
            $table->decimal('amount', 8, 2);
            $table->boolean('billable');
            $table->string('unreported')->default('unreported');           
            $table->string('invoiced')->default('non invoiced');  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
