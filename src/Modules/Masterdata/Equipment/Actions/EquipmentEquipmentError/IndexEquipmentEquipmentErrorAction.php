<?php

declare(strict_types=1);

namespace Modules\Masterdata\Equipment\Actions\EquipmentEquipmentError;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Equipment\ErrorMonitoring\Models\EquipmentErrorLog;

final class IndexEquipmentEquipmentErrorAction
{
    use AsAction;

    public function asController(Request $request): JsonResponse
    {
        $definitions = EquipmentErrorLog::query()
            ->whereNull('occurred_at')
            ->get();

        return response()->json($definitions);
    }
}
