<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('users', static function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('avatar')->nullable()->comment('Avatar URL');
            $table->string('address')->nullable()->comment('User address');
            $table->tinyInteger('status')->default(1)->comment('User status, 1 for active, 2 for inactive');
            $table->rememberToken();
            $table->string('phone')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
