
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('video_assets', function (Blueprint $table) {

            $table->id();

            $table->foreignId('content_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('episode_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('type', ['movie', 'episode', 'trailer']);

            $table->string('quality')->nullable(); // 480p,720p,1080p

            $table->string('path');

            $table->integer('duration')->nullable();

            $table->string('mime_type')->nullable();

            $table->bigInteger('size')->nullable();

            $table->boolean('is_default')->default(false);

            $table->boolean('is_processed')->default(false);

            $table->text('error')->nullable();

            $table->timestamps();

            $table->softDeletes();

            $table->index('content_id');
            $table->index('episode_id');
        });
    }
    public function down()
    {
        Schema::dropIfExists('video_assets');
    }
};
