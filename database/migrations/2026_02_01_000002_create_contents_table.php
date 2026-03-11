<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('contents', function (Blueprint $table) {

      $table->id();

      $table->string('title');
      $table->string('slug')->unique();
      $table->string('original_title')->nullable();

      $table->enum('type', [
        'movie',
        'series',
        'show',
        'documentary',
        'kids'
      ])->default('movie');

      $table->longText('description')->nullable();
      $table->text('short_description')->nullable();

      $table->string('language', 20)->nullable();
      $table->string('country', 10)->nullable();

      $table->string('maturity_rating', 20)->nullable();

      $table->year('release_year')->nullable();
      $table->date('release_date')->nullable();

      $table->integer('duration')->nullable();

      $table->boolean('is_featured')->default(false);
      $table->boolean('is_trending')->default(false);
      $table->boolean('is_recommended')->default(false);

      $table->unsignedBigInteger('views_count')->default(0);

      $table->string('poster')->nullable();
      $table->string('thumbnail')->nullable();
      $table->string('backdrop')->nullable();
      $table->string('logo')->nullable();

      $table->decimal('imdb_rating', 3, 1)->nullable();
      $table->decimal('avg_rating', 3, 1)->nullable();

      $table->boolean('is_published')->default(false);
      $table->timestamp('published_at')->nullable();

      $table->timestamps();
      $table->softDeletes();

      $table->index(['type', 'is_published']);
      $table->index(['is_featured', 'is_published']);
      $table->index('language');
      $table->index('slug');
    });
  }
  public function down(): void
  {
    Schema::dropIfExists('contents');
  }
};
