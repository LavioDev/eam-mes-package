<?php

declare(strict_types=1);

namespace Modules\Equipment\Checklist\Infrastructure\Helpers;

use Modules\Masterdata\Unit\Infrastructure\Models\Unit;
use Modules\Masterdata\Product\Infrastructure\Models\Product;
use Modules\Equipment\Checklist\Infrastructure\Models\ChecklistSession;
use Illuminate\Support\Facades\Log;
use Modules\Inventory\StockPicking\Domain\Actions\Resources\StoreStockPickingAction;
use Modules\Sale\Order\Infrastructure\Models\SaleOrderItem;
// use Modules\Inventory\ChecklistSession\Domain\Services\ChecklistSessionService;

class StockHelper
{
    public static function getAvailableQuantity(Product $product): int
    {
        try {
            return (int) ChecklistSession::where('product_id', $product->id)->sum('quantity');
        } catch (\Throwable $e) {
            Log::error('Failed to get available quantity: ' . $e->getMessage());
            return 0;
        }
    }

    public static function makeMaterialBuyCommand(Product $product, float $quantity, Unit $unit, ?string $saleOrderItemId): void
    {
        // 1. Determine scheduled date from SaleOrder (fallback to now)
        $saleOrderItem = $saleOrderItemId
            ? SaleOrderItem::with('saleOrder')->find($saleOrderItemId)
            : null;
        $scheduledDate = $saleOrderItem?->saleOrder?->scheduled_date ?? now()->format('Y-m-d H:i:s');

        // 2. Generate a random key
        $key = (string) random_int(1000000000, 2000000000);

        // 3. Build the item array
        $item = [
            'key' => $key,
            'product_id' => $product->id,
            'quantity' => $quantity,
            'unit_id' => $unit->id,
            'lot_amount' => 1,
            'previous_lot_amount' => 1,
            'lot_no' => now()->format('dm'),
            'lots' => [
                [
                    'quantity' => $quantity,
                    'unit_id' => $unit->id,
                ],
            ],
        ];

        // 4. Call the action to create a StockPicking
        // Using defaults for location_id and location_dest_id
        StoreStockPickingAction::run(
            'incoming',              // picking type
            null,                // default source location id
            null,                 // default destination location id
            $scheduledDate,          // scheduled date
            'Lệnh nhập kho',         // name
            $saleOrderItemId,        // origin
            null,                    // partner_id
            [$item]                  // items array
        );
    }

    public static function reverseProductQuantity(Product $product, float $requiredQuantity, Unit $unit, ?string $saleOrderItemId): void
    {
        // PART 1: CREATE STOCK PICKING
        // 1. Get scheduled date from sale order or default to now
        $saleOrderItem = $saleOrderItemId
            ? SaleOrderItem::with('saleOrder')->find($saleOrderItemId)
            : null;
        $scheduledDate = $saleOrderItem?->saleOrder?->scheduled_date ?? now()->format('Y-m-d H:i:s');

        // 2. Generate a random key for the item
        $key = (string) random_int(1000000000, 2000000000);

        // 3. Build item array
        // For outgoing moves, you typically don’t specify lots yet
        $item = [
            'key' => $key,
            'product_id' => $product->id,
            'quantity' => $requiredQuantity,
            'unit_id' => $unit->id,
            'lot_amount' => 1,
            'previous_lot_amount' => 1,
            'lot_no' => now()->format('dm'),
            'lots' => [
                [
                    'quantity' => $requiredQuantity,
                    'unit_id' => $unit->id,
                ],
            ],
        ];

        // 4. Call the action to create an outgoing StockPicking
        StoreStockPickingAction::run(
            'outgoing',             // picking type
            null,                // default source (temporary)
            null,             // default destination (temporary)
            $scheduledDate,
            'Lệnh xuất kho',        // name
            $saleOrderItemId,       // origin
            null,                   // partner_id (unknown for now)
            [$item]
        );
    }
}
