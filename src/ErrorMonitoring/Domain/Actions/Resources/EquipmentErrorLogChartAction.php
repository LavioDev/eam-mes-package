<?php

declare(strict_types=1);

namespace Modules\Equipment\ErrorMonitoring\Domain\Actions\Resources;

use App\Concerns\HasApiResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Equipment\ErrorMonitoring\Infrastructure\Models\EquipmentErrorLog;
use Modules\Masterdata\Equipment\Infrastructure\Models\Equipment;
use Throwable;

final class EquipmentErrorLogChartAction
{
    use HasApiResponse, AsAction;

    public function asController(Request $request): JsonResponse
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }

    public function execute(Request $request): array
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }


    private function equipmentErrorLogChartData($errorLogs, $limit): array
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }
}
