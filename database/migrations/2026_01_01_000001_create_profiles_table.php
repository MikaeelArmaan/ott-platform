<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {

        Schema::create('profiles', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');

            $table->string('slug')->nullable()->index();

            $table->string('avatar')->nullable();

            $table->string('avatar_type')->nullable(); // preset/custom

            $table->boolean('is_kids')->default(false);

            $table->enum('maturity_level', [
                'U',
                'UA',
                '12+',
                '16+',
                '18+'
            ])->default('U');

            $table->string('pin_hash')->nullable();

            $table->unsignedInteger('position')->default(0);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('profiles');
    }
};
