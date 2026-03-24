
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('genres', function (Blueprint $table) {

            $table->id();

            $table->string('name')->unique();

            $table->string('slug')->unique();

            $table->text('description')->nullable();

            // ✅ UI / UX
            $table->string('icon')->nullable(); // for UI chips
            $table->string('color')->nullable(); // for tags

            // ✅ Control
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);

            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('genres');
    }
};
