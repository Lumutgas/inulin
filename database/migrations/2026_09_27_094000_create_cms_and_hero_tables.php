<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_slides', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('subtitle')->nullable();
            $table->string('badge')->nullable();
            $table->string('image');
            $table->string('button_text')->nullable();
            $table->string('button_link')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('page_contents', function (Blueprint $table) {
            $table->id();
            $table->string('page')->index(); // home, about, how_it_works
            $table->string('section_key')->index(); // unique identifier within page
            $table->string('title');
            $table->text('subtitle')->nullable();
            $table->string('badge')->nullable();
            $table->longText('content')->nullable();
            $table->string('image')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('workflow_steps', function (Blueprint $table) {
            $table->id();
            $table->integer('step_number')->default(1);
            $table->string('title');
            $table->string('duration')->nullable();
            $table->string('tag')->nullable();
            $table->string('icon')->nullable();
            $table->text('description');
            $table->text('note')->nullable();
            $table->string('image')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('about_pillars', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('pillar'); // pillar, equipment, metric
            $table->string('title');
            $table->text('subtitle')->nullable();
            $table->string('icon')->nullable();
            $table->text('description')->nullable();
            $table->string('highlight')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('about_pillars');
        Schema::dropIfExists('workflow_steps');
        Schema::dropIfExists('page_contents');
        Schema::dropIfExists('hero_slides');
    }
};
