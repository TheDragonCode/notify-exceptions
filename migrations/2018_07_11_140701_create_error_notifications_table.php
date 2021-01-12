<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateErrorNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('error_notifications', function (Blueprint $table) {
            $table->unsignedBigInteger('id');

            $table->string('parent');
            $table->longText('exception');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('error_notifications');
    }
}
