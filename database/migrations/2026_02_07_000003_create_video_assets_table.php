<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('video_assets', function (Blueprint $t) {
      $t->id();
      $t->foreignId('content_id')->constrained('contents')->cascadeOnDelete();
      $t->unsignedBigInteger('episode_id')->nullable(); // Phase 2 (series)
      $t->text('source_url');        // uploaded file path/url
      $t->text('hls_master_url')->nullable(); // generated HLS master url
      $t->string('status', 20)->default('uploaded'); // uploaded/transcoding/ready/failed
      $t->integer('duration_seconds')->nullable();
      $t->timestamps();
      $t->index(['content_id','status']);
    });
  }
  public function down(): void { Schema::dropIfExists('video_assets'); }
};
