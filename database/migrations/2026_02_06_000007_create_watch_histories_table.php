
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::create('watch_histories', function (Blueprint $table) {

            $table->id();

            $table->foreignId('profile_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('content_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('episode_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();

            $table->timestamp('watched_at')->nullable();

            $table->integer('watch_time_seconds')->default(0);

            $table->decimal('completion_percent', 5, 2)->nullable();

            $table->boolean('completed')->default(false);

            $table->timestamps();

            $table->index('profile_id');
            $table->index('content_id');
            $table->index('episode_id');
            $table->index('watched_at');

            $table->unique([
                'profile_id',
                'content_id',
                'episode_id'
            ]);
        });
    }

    public function down()
    {
        Schema::dropIfExists('watch_histories');
    }
};
