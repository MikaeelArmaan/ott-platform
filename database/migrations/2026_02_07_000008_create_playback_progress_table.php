
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('playback_progress', function (Blueprint $table) {

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

            $table->foreignId('video_asset_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->integer('position_seconds')->default(0);

            $table->integer('duration_seconds')->default(0);

            $table->decimal('completion_percent', 5, 2)->default(0);

            $table->boolean('is_completed')->default(false);

            $table->timestamp('last_watched_at')->nullable();

            $table->timestamps();

            $table->unique(['profile_id', 'content_id', 'episode_id']);

            $table->index('profile_id');
            $table->index('content_id');
            $table->index('episode_id');
            $table->index('last_watched_at');
        });
    }
    public function down()
    {
        Schema::dropIfExists('playback_progress');
    }
};
