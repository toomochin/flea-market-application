<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'postcode')) {
                $table->string('postcode', 20)->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'address')) {
                $table->string('address', 255)->nullable()->after('postcode');
            }
            if (!Schema::hasColumn('users', 'building')) {
                $table->string('building', 255)->nullable()->after('address');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'building'))
                $table->dropColumn('building');
            if (Schema::hasColumn('users', 'address'))
                $table->dropColumn('address');
            if (Schema::hasColumn('users', 'postcode'))
                $table->dropColumn('postcode');
        });
    }
};
