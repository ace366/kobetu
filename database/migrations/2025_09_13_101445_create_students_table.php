<?php
// database/migrations/xxxx_xx_xx_create_students_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('last_name');        // 姓（漢字）
            $table->string('first_name');       // 名（漢字）
            $table->string('last_name_kana');   // 姓（かな）
            $table->string('first_name_kana');  // 名（かな）
            $table->string('school')->nullable();
            $table->string('grade')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->unique();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('students');
    }
};
