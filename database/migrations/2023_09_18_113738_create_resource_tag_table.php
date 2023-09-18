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
        Schema::create('resource_tag', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tag_id')
                ->references('id')
                ->on('tags');
            $table->uuid('resource_id')
                ->references('id')
                ->on('resources');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource_tag');
    }
};
