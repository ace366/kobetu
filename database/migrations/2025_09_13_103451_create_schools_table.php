<?php

// database/migrations/xxxx_xx_xx_create_schools_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();   // 学校名
            $table->string('city')->nullable(); // 市区町村
            $table->string('prefecture')->nullable(); // 県
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('schools');
    }
};
