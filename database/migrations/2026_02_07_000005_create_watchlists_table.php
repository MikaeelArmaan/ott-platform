<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('watchlists', function (Blueprint $t) {
      $t->id();
      $t->unsignedBigInteger('profile_id');
      $t->unsignedBigInteger('content_id');
      $t->timestamps();
      $t->unique(['profile_id','content_id']);
    });
  }
  public function down(): void { Schema::dropIfExists('watchlists'); }
};
