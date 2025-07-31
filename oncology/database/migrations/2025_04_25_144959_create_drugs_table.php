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
        Schema::create('drugs', function (Blueprint $table) {
             $table->id();
    $table->string('name');
    $table->integer('quantity');
    $table->string('company');
    $table->string('country');
    $table->date('expiration_date');
    $table->unsignedBigInteger('pharmacist_id');
        $table->foreign('pharmacist_id')->references('id')->on('users');
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drugs');
    }
};
