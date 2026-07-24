<?php

declare(strict_types=1);

namespace Modules\Masterdata\Equipment\Actions\EquipmentEquipmentError;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Equipment\ErrorMonitoring\Models\EquipmentErrorLog;

final class StoreEquipmentEquipmentErrorAction
{
    use AsAction;

    public function asController(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'equipment_id' => ['required', 'string', 'max:36'],
            'equipment_error_id' => ['required', 'string', 'max:36'],
        ]);

        $log = EquipmentErrorLog::create([
            'equipment_id' => $validated['equipment_id'],
            'equipment_error_id' => $validated['equipment_error_id'],
            'occurred_at' => null,
        ]);

        return response()->json($log, 201);
    }
}
