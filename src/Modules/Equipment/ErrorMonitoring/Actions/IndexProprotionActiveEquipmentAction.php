<?php

declare(strict_types=1);

namespace Modules\Equipment\ErrorMonitoring\Actions;

use App\Concerns\HasApiResponse;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Equipment\ErrorMonitoring\Models\EquipmentErrorLog;
use Modules\Masterdata\Equipment\Infrastructure\Models\Equipment;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

final class IndexProprotionActiveEquipmentAction
{


    use HasApiResponse, AsAction;

    public function asController(Request $request): JsonResponse
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }
}
