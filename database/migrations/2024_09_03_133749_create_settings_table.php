<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sensor_id')->constrained()->onDelete('cascade');
            $table->foreignId('actuator_id')->constrained()->onDelete('cascade'); // Menambahkan kolom actuator_id
            $table->decimal('min_value', 8, 2);
            $table->decimal('max_value', 8, 2);
            $table->string('actuator_action'); // Aksi yang diambil oleh aktuator
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
