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


        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('book_img');
            $table->string('author'); 
            $table->unsignedBigInteger('section_id'); // Use unsignedBigInteger instead of unsignedInteger
            $table->date('published_date');
            $table->string("description");
            $table->enum('status',["available","not available"]);
        
            $table->foreign("section_id")->references("id")->on("sections")->onDelete("cascade");
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
