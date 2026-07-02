<?php

declare(strict_types=1);

namespace Modules\Equipment\ParameterLog\Domain\Actions\Resources;

use App\Concerns\HasApiResponse;
use Carbon\CarbonImmutable;
use Modules\Equipment\ErrorMonitoring\Infrastructure\Models\EquipmentErrorLog;
use Modules\Manufacturing\MRP\Infrastructure\Models\TimeShift;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Equipment\ErrorMonitoring\Domain\Actions\Resources\IndexOperatingTimeAction;
use Modules\Masterdata\Equipment\Infrastructure\Models\Equipment;

final class OverviewEquipmentParameterLogAction
{
    use AsAction, HasApiResponse;

    public function asController(Request $request, string $equipmentId): JsonResponse
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }

    private function calculateShiftTime($timeShifts, $equipmentErrorLog, $startTime, $endTime)
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }

    private function calculateUnplannedStopTime($shiftStart, $shiftEnd, $equipmentErrorLog)
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }
}
