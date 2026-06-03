<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        //! Crear tabla empleados
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('dni')->unique();
            $table->string('email')->unique();
            $table->date('birth_date');
            $table->foreignId('area_id')->constrained('areas')->restrictOnDelete();
            $table->foreignId('position_id')->constrained('positions')->restrictOnDelete();
            $table->foreignId('location_id')->constrained('locations')->restrictOnDelete();
            $table->timestamps();
            $table->softDeletes(); // BORRADO LOGICOO
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
