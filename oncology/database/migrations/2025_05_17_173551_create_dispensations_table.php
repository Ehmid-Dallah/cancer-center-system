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
        Schema::create('dispensations', function (Blueprint $table) {
           $table->id();
        $table->unsignedBigInteger('prescription_id');
        $table->unsignedBigInteger('pharmacist_id'); // الصيدلي الحالي
        $table->string('drug_name');
        $table->integer('quantity');
        $table->date('dispensed_at');
        $table->text('notes')->nullable();
        $table->timestamps();

        $table->foreign('prescription_id')->references('id')->on('prescriptions');
        $table->foreign('pharmacist_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispensations');
    }
};
