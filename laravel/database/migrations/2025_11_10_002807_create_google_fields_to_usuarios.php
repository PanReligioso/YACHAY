<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            if (!Schema::hasColumn('usuarios', 'google_id')) {
                $table->string('google_id')->nullable()->unique()->after('email');
            }
            if (!Schema::hasColumn('usuarios', 'avatar')) {
                $table->string('avatar')->nullable()->after('google_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            if (Schema::hasColumn('usuarios', 'google_id')) {
                $table->dropColumn('google_id');
            }
            if (Schema::hasColumn('usuarios', 'avatar')) {
                $table->dropColumn('avatar');
            }
        });
    }
};
