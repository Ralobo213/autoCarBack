<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehicules', function (Blueprint $table) {
            $table->id();
            $table->text('photo');
            $table->string('marque');
            $table->string('matricule');
            $table->text('description');
            $table->string('prix');
            $table->enum('porte',['2','3','4','5']);
            $table->string('place');
            $table->string('bagage');
            $table->enum('transmission',['Automatique','Manuelle']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicules');
    }
};
