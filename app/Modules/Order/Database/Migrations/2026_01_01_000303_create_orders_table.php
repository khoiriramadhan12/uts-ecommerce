<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->foreignId('customer_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('address_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string('status', 20)->default('pending');
            $table->decimal('subtotal', 14, 2);
            $table->decimal('shipping_cost', 14, 2)->default(0);
            $table->decimal('total', 14, 2);
            $table->json('shipping_snapshot');
            $table->timestamp('placed_at')->nullable();
            $table->timestamps();

            $table->index(['customer_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
