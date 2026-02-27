<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('address')->nullable()->after('email');
            $table->string('contacts')->nullable()->after('address');
            $table->string('avatar')->nullable()->default('media/avatars/blank.png')->after('contacts');
            $table->integer('department')->after('avatar');
            $table->integer('position')->after('department');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->dropColumn('contacts');
            $table->dropColumn('avatar');
            $table->dropColumn('department');
            $table->dropColumn('position');
        });
    }
};
