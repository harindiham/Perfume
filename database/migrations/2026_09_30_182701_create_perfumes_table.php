<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perfumes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
                ->constrained('categories')
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('brand');
            $table->decimal('price', 10, 2);
            $table->string('size')->nullable();

            $table->text('description')->nullable();

            $table->text('top_notes')->nullable();
            $table->text('middle_notes')->nullable();
            $table->text('base_notes')->nullable();

            $table->string('image')->nullable();

            $table->integer('stock')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perfumes');
    }
};

