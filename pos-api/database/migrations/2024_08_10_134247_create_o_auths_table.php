<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\OAuthType;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('oauths', function (Blueprint $table) {
            $table->id();
            $table->morphs("oauthable");
            $table->enum("type", array_map(fn($case) => $case->name, OAuthType::cases()));
            $table->string("email")->unique();
            $table->string("token");
            $table->string("refresh_token");
            $table->datetime("expire_in");
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oauths');
    }
};
