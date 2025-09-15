<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('holidays', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date')->unique();
            $table->string('name');
            $table->enum('category', ['national','gw','obon','yearend','other'])->default('other');
            $table->timestamps();
            $table->index(['date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('holidays');
    }
};