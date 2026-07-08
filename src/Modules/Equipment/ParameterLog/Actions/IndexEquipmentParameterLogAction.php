<?php

declare(strict_types=1);

namespace Modules\Equipment\ParameterLog\Actions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Equipment\ParameterLog\Models\EquipmentParameterLog;
use Throwable;

final class IndexEquipmentParameterLogAction
{

    use AsAction;public function asController(Request $request): JsonResponse
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }
}
