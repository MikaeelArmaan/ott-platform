<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('seasons', function (Blueprint $t) {
      $t->id();
      $t->foreignId('content_id')->constrained('contents')->cascadeOnDelete(); // series id
      $t->unsignedInteger('season_number'); // 1,2,3...
      $t->string('title')->nullable(); // "Season 1"
      $t->text('description')->nullable();
      $t->timestamps();

      $t->unique(['content_id','season_number']);
      $t->index(['content_id']);
    });
  }

  public function down(): void {
    Schema::dropIfExists('seasons');
  }
};
