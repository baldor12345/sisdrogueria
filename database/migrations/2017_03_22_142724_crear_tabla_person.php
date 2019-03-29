<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaPerson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person', function (Blueprint $table) {
            $table->increments('id');
            $table->string('lastname',100)->nullable();
            $table->string('firstname',100)->nullable();
            $table->string('bussinesname',100)->nullable();
            $table->char('dni',8)->nullable();
            $table->char('ruc',11)->nullable();
            $table->string('address',120)->nullable();
            $table->string('phonenumber',15)->nullable();
            $table->string('cellnumber',15)->nullable();
            $table->string('email',30)->nullable();
            $table->date('birthdate')->nullable();
            $table->integer('workertype_id')->unsigned()->nullable();
            $table->text('observation')->nullable();
            $table->char('type',1)->nullable(); // customer C, provider P, employee E
            $table->char('secondtype',1)->nullable(); // company C , person P.
            $table->foreign('workertype_id')->references('id')->on('workertype')->onUpdate('restrict')->onDelete('restrict');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('person');
    }
}
