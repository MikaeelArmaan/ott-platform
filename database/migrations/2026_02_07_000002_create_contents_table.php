<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('contents', function (Blueprint $t) {
      $t->id();
      $t->string('type', 10); // movie/series
      $t->string('title', 255);
      $t->text('description')->nullable();
      $t->string('language', 50)->nullable();
      $t->date('release_date')->nullable();
      $t->integer('runtime_seconds')->nullable(); // for movies
      $t->text('poster_url')->nullable();
      $t->text('thumbnail_url')->nullable();
      $t->text('backdrop_url')->nullable();
      $t->string('maturity_rating', 10)->default('U');
      $t->boolean('is_published')->default(false);
      $t->timestamps();
      $t->index(['type', 'is_published']);
    });
  }
  public function down(): void { Schema::dropIfExists('contents'); }
};
