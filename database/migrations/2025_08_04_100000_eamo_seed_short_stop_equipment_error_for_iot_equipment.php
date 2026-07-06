<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Modules\Equipment\ErrorMonitoring\Models\EquipmentErrorLog;
use Modules\Masterdata\Equipment\Models\Equipment;
use Modules\Masterdata\Equipment\Models\EquipmentError;

return new class extends Migration
{
    private const SHORT_STOP_EQUIPMENT_ERROR_ID = 'short_stop';

    public function up(): void
    {
        // skip if not pgsql
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        DB::transaction(static function (): void {
            $shortStopError = EquipmentError::query()
                ->withoutGlobalScopes()
                ->where('id', self::SHORT_STOP_EQUIPMENT_ERROR_ID)
                ->orWhere('name', 'Short stop')
                ->first();

            if (! $shortStopError) {
                $shortStopError = new EquipmentError();
                $shortStopError->id = self::SHORT_STOP_EQUIPMENT_ERROR_ID;
                $shortStopError->name = 'Short stop';
                $shortStopError->save();
            } elseif ($shortStopError->id !== self::SHORT_STOP_EQUIPMENT_ERROR_ID) {
                $legacyShortStopError = $shortStopError;

                $shortStopError = new EquipmentError();
                $shortStopError->id = self::SHORT_STOP_EQUIPMENT_ERROR_ID;
                $shortStopError->name = 'Short stop';
                $shortStopError->save();

                $legacyEquipmentIds = $legacyShortStopError->equipment()->pluck('id')->toArray();
                foreach (array_chunk($legacyEquipmentIds, 1000) as $equipmentIdChunk) {
                    $shortStopError->equipment()->syncWithoutDetaching($equipmentIdChunk);
                }

                EquipmentErrorLog::query()
                    ->where('equipment_error_id', $legacyShortStopError->id)
                    ->update(['equipment_error_id' => $shortStopError->id]);

                $legacyShortStopError->delete();
            }

            $equipmentIds = Equipment::query()
                ->whereNotNull('device_id')
                ->pluck('id')
                ->toArray();

            foreach (array_chunk($equipmentIds, 1000) as $equipmentIdChunk) {
                $shortStopError->equipment()->syncWithoutDetaching($equipmentIdChunk);
            }
        });
    }

    public function down(): void
    {
        DB::transaction(static function (): void {
            $shortStopError = EquipmentError::query()
                ->withoutGlobalScopes()
                ->where('id', self::SHORT_STOP_EQUIPMENT_ERROR_ID)
                ->orWhere('name', 'Short stop')
                ->first();

            if (! $shortStopError) {
                return;
            }

            $shortStopError->equipment()->detach();
            $shortStopError->delete();
        });
    }
};
