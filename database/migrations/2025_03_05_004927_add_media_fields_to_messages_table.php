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
        Schema::table('messages', function (Blueprint $table) {
            $table->string('media_url')->nullable();
            $table->string('media_type')->nullable();
            // Make content column nullable if it exists
            if (Schema::hasColumn('messages', 'content')) {
                $table->string('content')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn('media_url');
            $table->dropColumn('media_type');
            // Revert content column to not nullable if it exists
            if (Schema::hasColumn('messages', 'content')) {
                $table->string('content')->nullable(false)->change();
            }
        });
    }
};
