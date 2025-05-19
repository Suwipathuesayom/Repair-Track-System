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
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('repair_requests', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('department_id')->constrained('departments');
            $table->string('status');
            $table->string('urgency_level');
            $table->timestamp('sla_due_at')->nullable();
            $table->timestamp('pause_sla_start_at')->nullable();
            $table->timestamp('pause_sla_end_at')->nullable();
            $table->timestamps();
        });

        Schema::create('repair_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_request_id')->constrained('repair_requests')->onDelete('cascade');
            $table->string('file_path');
            $table->string('file_type');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('repair_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_request_id')->constrained('repair_requests')->onDelete('cascade');
            $table->foreignId('changed_by_user_id')->constrained('users');
            $table->string('from_status');
            $table->string('to_status');
            $table->text('note')->nullable();
            $table->timestamp('created_at')->nullable();
        });


        Schema::create('repair_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_request_id')->constrained('repair_requests')->onDelete('cascade');
            $table->foreignId('technician_id')->constrained('users');
            $table->integer('level');
            $table->text('action_details');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('parts_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_request_id')->constrained('repair_requests')->onDelete('cascade');
            $table->foreignId('requested_by_user_id')->constrained('users');
            $table->string('part_name');
            $table->integer('quantity');
            $table->string('status');
            $table->foreignId('approved_by_user_id')->constrained('users');
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->string('value');
            $table->timestamps();
        });


        Schema::create('parts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('stock')->default(0);
            $table->timestamps();
        });

        DB::table('departments')->insert([
            ['name' => 'ฝ่ายไอที'],
            ['name' => 'ฝ่ายขาย'],
            ['name' => 'ฝ่ายบัญชี'],
        ]);

        DB::table('roles')->insert([
            ['name' => 'user'],
            ['name' => 'technician_lvl1'],
            ['name' => 'technician_lvl2'],
            ['name' => 'it_manager'],
        ]);

        DB::table('users')->insert([
            [
                'name' => 'สมชาย ใจดี',
                'email' => 'user1@example.com',
                'email_verified_at' => now(),
                'password' => 'password123',
                'remember_token' => Str::random(10),
                'department_id' => 3,
                'role_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ศักดิ์สิทธิ์ พานทอง',
                'email' => 'user2@example.com',
                'email_verified_at' => now(),
                'password' => 'password123',
                'remember_token' => Str::random(10),
                'department_id' => 2,
                'role_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'สุภาพร ยิ้มแย้ม',
                'email' => 'user3@example.com',
                'email_verified_at' => now(),
                'password' => 'password123',
                'remember_token' => Str::random(10),
                'department_id' => 1,
                'role_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'สายฝน ใจงาม',
                'email' => 'user4@example.com',
                'email_verified_at' => now(),
                'password' => 'password123',
                'remember_token' => Str::random(10),
                'department_id' => 2,
                'role_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'อนุชา นุ่มนวล',
                'email' => 'user5@example.com',
                'email_verified_at' => now(),
                'password' => 'password123',
                'remember_token' => Str::random(10),
                'department_id' => 2,
                'role_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('settings')->insert([
            ['key' => 'sla_critical_response', 'value' => '1 hour', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'sla_high_response', 'value' => '4 hours', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'sla_normal_response', 'value' => '1 day', 'created_at' => now(), 'updated_at' => now()],
        ]);


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
        Schema::dropIfExists('settings');
        Schema::dropIfExists('parts_requests');
        Schema::dropIfExists('repair_actions');
        Schema::dropIfExists('repair_logs');
        Schema::dropIfExists('repair_attachments');
        Schema::dropIfExists('repair_requests');
        Schema::dropIfExists('parts');
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('departments');
    }
};
