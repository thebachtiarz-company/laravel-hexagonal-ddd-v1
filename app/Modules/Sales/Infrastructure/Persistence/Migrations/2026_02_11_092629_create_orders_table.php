<?php

declare(strict_types=1);

use App\Models\User;
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
        Schema::create(OrderModel::TABLE, function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(User::class, OrderModel::USER_ID)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string(OrderModel::CODE)->unique();
            $table->string(OrderModel::STATUS)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(OrderModel::TABLE);
    }
};
