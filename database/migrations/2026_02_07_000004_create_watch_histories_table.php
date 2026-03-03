<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('watch_histories', function (Blueprint $t) {
      $t->id();
      $t->unsignedBigInteger('profile_id');
      $t->unsignedBigInteger('content_id');
      $t->unsignedBigInteger('episode_id')->nullable();
      $t->integer('position_seconds')->default(0);
      $t->integer('duration_seconds')->default(0);
      $t->boolean('completed')->default(false);
      $t->timestamp('last_watched_at')->useCurrent();
      $t->unique(['profile_id','content_id','episode_id']);
      $t->index(['profile_id','completed']);
      $t->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('watch_histories'); }
};
