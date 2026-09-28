<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('canvas_topics', function (Blueprint $table): void {
            $table->string('featured_image')->nullable()->after('name');
            $table->string('featured_image_caption')->nullable()->after('featured_image');
        });
    }

    public function down(): void
    {
        Schema::table('canvas_topics', function (Blueprint $table): void {
            $table->dropColumn(['featured_image', 'featured_image_caption']);
        });
    }
};
