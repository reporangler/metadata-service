<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('package', function (Blueprint $table) {
            $table->string('storage_key')->nullable()->after('definition');
            $table->string('package_type')->nullable()->after('storage_key');
        });
    }

    public function down(): void
    {
        Schema::table('package', function (Blueprint $table) {
            $table->dropColumn(['storage_key', 'package_type']);
        });
    }
};
