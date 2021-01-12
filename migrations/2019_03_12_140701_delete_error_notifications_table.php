<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteErrorNotificationsTable extends Migration
{
    public function up()
    {
        Schema::dropIfExists('error_notifications');
    }

    public function down()
    {
        Schema::create('error_notifications', function (Blueprint $table) {
            $table->unsignedBigInteger('id');

            $table->string('parent');
            $table->longText('exception');

            $table->timestamps();
        });
    }
}
