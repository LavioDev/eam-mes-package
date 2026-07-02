<?php

declare(strict_types=1);

namespace Modules\Equipment\ErrorMonitoring\Domain\Actions\Resources;

use App\Concerns\HasApiResponse;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Modules\Equipment\ErrorMonitoring\Infrastructure\Models\EquipmentErrorLog;
use Throwable;

final class IndexEquipmentErrorLogToDayAction
{
    use AsAction, HasApiResponse;

    public function asController(Request $request): JsonResponse
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }
}
