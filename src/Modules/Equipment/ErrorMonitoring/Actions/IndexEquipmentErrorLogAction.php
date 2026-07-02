<?php

declare(strict_types=1);

namespace Modules\Equipment\ErrorMonitoring\Actions;

use App\Concerns\HasApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Equipment\ErrorMonitoring\Models\EquipmentErrorLog;
use Throwable;

final class IndexEquipmentErrorLogAction
{

    use AsAction, HasApiResponse;

    public function asController(Request $request): JsonResponse
         {
        // TODO: Implement custom logic
        return response()->json([]);
    }
}
