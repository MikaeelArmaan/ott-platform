<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('profiles', function (Blueprint $t) {
      $t->id();
      $t->foreignId('user_id')->constrained()->cascadeOnDelete();
      $t->string('name', 60);
      $t->boolean('is_kids')->default(false);
      $t->string('maturity_level', 10)->default('U'); // U/UA/12+/16+/18+
      $t->string('pin_hash')->nullable();
      $t->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('profiles'); }
};
