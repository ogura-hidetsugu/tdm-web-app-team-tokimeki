<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId('item_id')->nullable()->constrained();
            $table->string('kind', 20);
            $table->string('message', 200);
            $table->date('due_date')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->string('dedupe_key', 100)->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_notifications');
    }
};
