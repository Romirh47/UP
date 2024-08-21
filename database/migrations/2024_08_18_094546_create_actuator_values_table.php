<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActuatorValuesTable extends Migration
{
    public function up()
    {
        Schema::create('actuator_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actuator_id')
                ->constrained('actuators')
                ->onDelete('cascade'); // Menghapus nilai actuator jika actuator dihapus
            $table->boolean('value'); // 0 for Off, 1 for On
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('actuator_values');
    }
}
