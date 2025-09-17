<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 部署ID（NULL可）
            $table->foreignId('department_id')
                  ->nullable()
                  ->after('id')
                  ->constrained()
                  ->cascadeOnUpdate()
                  ->nullOnDelete();

            // 役職ID（NULL可）
            $table->foreignId('position_id')
                  ->nullable()
                  ->after('department_id')
                  ->constrained()
                  ->cascadeOnUpdate()
                  ->nullOnDelete();

            // 権限（1=一般, 2=管理）
            $table->tinyInteger('role')
                  ->unsigned()
                  ->default(1)
                  ->after('password');

            $table->index('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('department_id');
            $table->dropConstrainedForeignId('position_id');
            $table->dropIndex(['role']);
            $table->dropColumn(['department_id', 'position_id', 'role']);
        });
    }
};
