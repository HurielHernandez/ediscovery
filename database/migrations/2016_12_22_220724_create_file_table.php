<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('case_id')->unsigned();
            $table->integer('uploaded_by')->unsigned();
            $table->string('name');
            $table->string('original_name');
            $table->string('mime');
            $table->timestamps();
            $table->foreign('case_id')->references('id')->on('cases');
            $table->foreign('uploaded_by')->references('id')->on('users');
            

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('files');
    }
}
