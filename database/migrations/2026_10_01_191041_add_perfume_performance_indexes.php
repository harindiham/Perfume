<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perfumes', function (Blueprint $table) {
            $table->index('is_active', 'perfumes_is_active_index');
        });
    }

    public function down(): void
    {
        Schema::table('perfumes', function (Blueprint $table) {
            $table->dropIndex('perfumes_is_active_index');
        });
    }
};