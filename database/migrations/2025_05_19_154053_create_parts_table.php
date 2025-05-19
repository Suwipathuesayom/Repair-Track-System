<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('parts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('stock')->default(0);
            $table->timestamps();
        });



        DB::table('parts')->insert([
            ['name' => 'RAM DDR4 8GB', 'description' => 'หน่วยความจำสำหรับคอมพิวเตอร์', 'stock' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'SSD 512GB', 'description' => 'ฮาร์ดดิสก์แบบโซลิดสเตต', 'stock' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Mainboard MSI B450', 'description' => 'เมนบอร์ดรองรับ AMD', 'stock' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Power Supply 600W', 'description' => 'พาวเวอร์ซัพพลายมาตรฐาน', 'stock' => 12, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'CPU AMD Ryzen 5 5600X', 'description' => 'หน่วยประมวลผลกลาง', 'stock' => 4, 'created_at' => now(), 'updated_at' => now()],
        ]);

    }

    public function down(): void
    {
        Schema::dropIfExists('parts');
    }
};
