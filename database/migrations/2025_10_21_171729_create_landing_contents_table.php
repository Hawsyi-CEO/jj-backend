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
        Schema::create('landing_contents', function (Blueprint $table) {
            $table->id();
            $table->string('section'); // hero, services, testimonials, footer
            $table->string('key'); // title, subtitle, description, etc
            $table->text('value'); // actual content
            $table->json('metadata')->nullable(); // additional data like features array, etc
            $table->timestamps();
            
            // Make section + key unique
            $table->unique(['section', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('landing_contents');
    }
};
