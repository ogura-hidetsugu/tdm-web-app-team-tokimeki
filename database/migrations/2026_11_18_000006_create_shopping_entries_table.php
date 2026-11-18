<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shopping_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained();
            $table->foreignId('item_id')->nullable()->constrained();
            $table->string('name', 100);
            $table->foreignId('store_id')->nullable()->constrained();
            $table->text('memo')->nullable();
            $table->string('status', 10)->default('未購入');
            $table->integer('sort_order')->nullable();
            $table->foreignId('added_by_user_id')->constrained('users');
            $table->foreignId('purchased_by_user_id')->nullable()->constrained('users');
            $table->timestamp('purchased_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shopping_entries');
    }
};
