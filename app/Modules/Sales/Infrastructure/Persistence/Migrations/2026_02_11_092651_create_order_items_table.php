<?php

declare(strict_types=1);

use App\Modules\Sales\Infrastructure\Persistence\Eloquent\Models\OrderItemModel;
use App\Modules\Sales\Infrastructure\Persistence\Eloquent\Models\OrderModel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(OrderItemModel::TABLE, function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(OrderModel::class, OrderItemModel::ORDER_ID)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string(OrderItemModel::SKU)->unique();
            $table->unsignedSmallInteger(OrderItemModel::QTY);
            $table->json(OrderItemModel::SNAPSHOT);
            $table->decimal(OrderItemModel::PRICE);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(OrderItemModel::TABLE);
    }
};
