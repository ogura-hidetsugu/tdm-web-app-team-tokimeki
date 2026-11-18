<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained();
            $table->foreignId('category_id')->nullable()->constrained();
            $table->foreignId('default_store_id')->nullable()->constrained('stores');
            $table->string('name', 100);
            $table->text('memo')->nullable();
            $table->string('photo_original_ref', 255)->nullable();
            $table->string('photo_cutout_ref', 255)->nullable();
            $table->string('photo_display', 10)->default('original');
            $table->string('product_url', 2048)->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->integer('purchase_cycle_days')->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->integer('reorder_level')->nullable();
            $table->timestamp('last_purchased_at')->nullable();
            $table->foreignId('created_by_user_id')->constrained('users');
            $table->timestamp('archived_at')->nullable();
            $table->integer('version')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
