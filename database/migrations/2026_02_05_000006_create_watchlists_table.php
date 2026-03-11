
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('watchlists', function (Blueprint $table) {

            $table->id();

            $table->foreignId('profile_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('content_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->integer('position')->nullable(); // optional ordering

            $table->timestamps();

            $table->unique(['profile_id', 'content_id']);

            $table->index('profile_id');
            $table->index('content_id');
        });
    }
    public function down()
    {
        Schema::dropIfExists('watchlists');
    }
};
