<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained();
            $table->foreignId('item_id')->nullable()->constrained();
            $table->foreignId('shopping_entry_id')->nullable()->constrained('shopping_entries');
            $table->foreignId('user_id')->constrained();
            $table->string('operation', 10);
            $table->integer('quantity_change')->nullable();
            $table->string('note', 200)->nullable();
            $table->string('operation_key', 64)->nullable()->unique();
            $table->timestamp('operated_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
