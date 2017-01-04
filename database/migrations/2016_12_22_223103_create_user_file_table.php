<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_file', function(Blueprint $table)
        {
            $table->primary(['file_id', 'user_id'])->unique();
            $table->integer('file_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('access_count');
            $table->nullableTimestamps();
            $table->foreign('file_id')->references('id')->on('files');
            $table->foreign('user_id')->references('id')->on('users');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_file');
    }
}
