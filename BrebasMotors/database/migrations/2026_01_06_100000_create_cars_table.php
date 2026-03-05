<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->boolean('is_new')->default(false);
            $table->string('segment')->nullable();
            $table->string('brand');
            $table->string('model');
            $table->decimal('price', 12, 2);
            $table->unsignedInteger('mileage')->default(0);
            $table->string('engine')->nullable();
            $table->unsignedSmallInteger('power')->nullable();
            $table->string('fuel')->nullable();
            $table->string('transmission')->nullable();
            $table->unsignedTinyInteger('doors')->nullable();
            $table->unsignedTinyInteger('seats')->nullable();
            $table->string('image_path')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
