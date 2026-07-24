<?php

declare(strict_types=1);

namespace Modules\Masterdata\Equipment\Actions\EquipmentEquipmentError;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Equipment\ErrorMonitoring\Models\EquipmentErrorLog;

final class UpdateEquipmentEquipmentErrorAction
{
    use AsAction;

    public function asController(Request $request, string $id): JsonResponse
    {
        $log = EquipmentErrorLog::query()
            ->whereNull('occurred_at')
            ->findOrFail($id);

        $validated = $request->validate([
            'equipment_id' => ['sometimes', 'string', 'max:36'],
            'equipment_error_id' => ['sometimes', 'string', 'max:36'],
        ]);

        $log->update($validated);

        return response()->json($log);
    }
}
