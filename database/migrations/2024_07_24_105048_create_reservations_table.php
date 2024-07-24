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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_client');
            $table->unsignedBigInteger('id_voiture');
            $table->string('DateDebut');
            $table->string('DateFin');
            $table->string('PriceTotal');
            $table->timestamps();

            $table->foreign('id_client')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_voiture')->references('id')->on('vehicules')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
