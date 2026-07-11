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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique(); //A slug is a URL-friendly version of a name. (Ex:Category Name =Home Decor ==> Slug =home-decor)
            $table->text('description')->nullable(); // nullable() means:This field is optional.
            $table->string('image')->nullable(); // nullable() means:This field is optional.
            $table->string('status')->efault('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
