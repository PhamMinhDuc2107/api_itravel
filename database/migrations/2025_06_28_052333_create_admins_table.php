<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration

{
    const STATUS_ADMIN_ACTIVE = 2;
    const STATUS_ADMIN_INACTIVE = 1;
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string("email")->unique();
            $table->string("name")->nullable();
            $table->string("password");
            $table->string("phone", 20)->nullable();
            $table->string("avatar")->nullable();
            $table->enum('status', [1, 2])->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};