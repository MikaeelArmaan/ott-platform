<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('episodes', function (Blueprint $t) {
      $t->id();
      $t->foreignId('season_id')->constrained('seasons')->cascadeOnDelete();
      $t->unsignedInteger('episode_number'); // 1,2,3...
      $t->string('title');
      $t->text('description')->nullable();
      $t->integer('runtime_seconds')->nullable();
      $t->date('release_date')->nullable();

      // Optional thumbnail per episode (Netflix-style)
      $t->text('thumbnail_url')->nullable();

      // Optional direct MP4 per episode (fallback). HLS will be in video_assets.
      $t->text('video_url')->nullable();

      $t->boolean('is_published')->default(false);
      $t->timestamps();

      $t->unique(['season_id','episode_number']);
      $t->index(['season_id','is_published']);
    });
  }

  public function down(): void {
    Schema::dropIfExists('episodes');
  }
};
