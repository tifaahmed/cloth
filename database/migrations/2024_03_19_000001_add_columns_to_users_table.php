<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'user_id')) {
                $table->integer('user_id');
            }
            if (Schema::hasColumn('users', 'image')) {
                $table->string('image')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'image')) {
                $table->string('image')->nullable(false)->change();
            }
            if (Schema::hasColumn('users', 'user_id')) {
                $table->dropColumn('user_id');
            }
        });
    }
};
