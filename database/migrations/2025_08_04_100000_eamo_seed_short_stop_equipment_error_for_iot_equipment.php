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

    private const EMERGENCY_STOP_EQUIPMENT_ERROR_ID = 'emergency_stop';

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

            // Seed Emergency Stop error
            $emergencyStopError = EquipmentError::query()
                ->withoutGlobalScopes()
                ->where('id', self::EMERGENCY_STOP_EQUIPMENT_ERROR_ID)
                ->orWhere('name', 'Emergency stop')
                ->first();

            if (! $emergencyStopError) {
                $emergencyStopError = new EquipmentError();
                $emergencyStopError->id = self::EMERGENCY_STOP_EQUIPMENT_ERROR_ID;
                $emergencyStopError->name = 'Emergency stop';
                $emergencyStopError->save();
            } elseif ($emergencyStopError->id !== self::EMERGENCY_STOP_EQUIPMENT_ERROR_ID) {
                $legacyEmergencyStopError = $emergencyStopError;

                $emergencyStopError = new EquipmentError();
                $emergencyStopError->id = self::EMERGENCY_STOP_EQUIPMENT_ERROR_ID;
                $emergencyStopError->name = 'Emergency stop';
                $emergencyStopError->save();

                $legacyEquipmentIds = $legacyEmergencyStopError->equipment()->pluck('id')->toArray();
                foreach (array_chunk($legacyEquipmentIds, 1000) as $equipmentIdChunk) {
                    $emergencyStopError->equipment()->syncWithoutDetaching($equipmentIdChunk);
                }

                EquipmentErrorLog::query()
                    ->where('equipment_error_id', $legacyEmergencyStopError->id)
                    ->update(['equipment_error_id' => $emergencyStopError->id]);

                $legacyEmergencyStopError->delete();
            }

            foreach (array_chunk($equipmentIds, 1000) as $equipmentIdChunk) {
                $emergencyStopError->equipment()->syncWithoutDetaching($equipmentIdChunk);
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

            if ($shortStopError) {
                $shortStopError->equipment()->detach();
                $shortStopError->delete();
            }

            $emergencyStopError = EquipmentError::query()
                ->withoutGlobalScopes()
                ->where('id', self::EMERGENCY_STOP_EQUIPMENT_ERROR_ID)
                ->orWhere('name', 'Emergency stop')
                ->first();

            if ($emergencyStopError) {
                $emergencyStopError->equipment()->detach();
                $emergencyStopError->delete();
            }
        });
    }
};
