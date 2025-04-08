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
        Schema::create('produtcs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->string('sku')->unique();
            $table->string('barcode')->unique();
            $table->string('image')->nullable();
            $table->integer('cost');
            $table->integer('price');
            $table->foreignId('company_id')->nullable()->constrained('companies');
            $table->foreignId('category_id')->nullable()->constrained('categories');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produtcs');
    }
};
