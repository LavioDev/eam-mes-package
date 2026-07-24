<?php

declare(strict_types=1);

namespace Modules\Masterdata\Equipment\Actions\EquipmentEquipmentError;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Equipment\ErrorMonitoring\Models\EquipmentErrorLog;

final class ShowEquipmentEquipmentErrorAction
{
    use AsAction;

    public function asController(string $id): JsonResponse
    {
        $log = EquipmentErrorLog::query()
            ->whereNull('occurred_at')
            ->findOrFail($id);

        return response()->json($log);
    }
}
