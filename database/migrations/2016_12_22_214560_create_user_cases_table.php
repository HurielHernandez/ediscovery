<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_case', function(Blueprint $table){
            $table->primary(['case_id', 'user_id'])->unique();
            $table->integer('case_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('access_by')->unsigned();
            $table->timestamp('access_on')->nullable();
            $table->timestamp('revoke_on')->nullable();
            $table->nullableTimestamps();
            $table->foreign('case_id')->references('id')->on('cases');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('access_by')->references('id')->on('users');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_case');
    }
}
