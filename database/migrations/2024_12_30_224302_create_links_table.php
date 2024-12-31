<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->string('image')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('links', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->string('destination_url');
            $table->string('final_url');
            $table->unsignedBigInteger('channel_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('channel_id')
                ->references('id')
                ->on('channels');
        });

        Schema::create('link_clicks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('link_id');
            $table->string('ip')->nullable();
            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->string('country')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('link_id')
                ->references('id')
                ->on('links');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('channels');
        Schema::dropIfExists('link_clicks');
        Schema::dropIfExists('links');
    }
};
