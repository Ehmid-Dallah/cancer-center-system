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
        Schema::table('patients', function (Blueprint $table) {
             $table->string('father_name')->nullable();
        $table->string('mother_name')->nullable();
        $table->string('nationality')->nullable();
        $table->enum('identity_type', ['جواز سفر', 'بطاقة شخصية'])->nullable();
        $table->string('identity_number')->nullable();
        $table->enum('gender', ['ذكر', 'أنثى'])->nullable();
        $table->string('birth_place')->nullable();
        $table->string('residence')->nullable();
        $table->string('phone1')->nullable();
        $table->string('phone2')->nullable();
        $table->date('infection_date')->nullable();
    });
            //
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
            'father_name', 'mother_name', 'nationality', 'identity_type',
            'identity_number', 'gender', 'birth_place', 'residence',
            'phone1', 'phone2', 'infection_date'
        ]);
            //
        });
    }
};
