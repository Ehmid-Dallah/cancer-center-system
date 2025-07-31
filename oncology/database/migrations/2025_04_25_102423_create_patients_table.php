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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            //$table->integer('id_patinet');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
          
           $table->string('file_number')->unique();
            $table->date('registration_date')->nullable();
  $table->string('email')->unique()->nullable(); // إيميل المريض
    $table->unsignedBigInteger('user_id')->nullable(); // ربط حساب المستخدم بالمريض

    $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
