<?php

declare(strict_types=1);

namespace Modules\Equipment\ErrorMonitoring\Domain\Actions\Resources;

use App\Concerns\HasApiResponse;
use Carbon\CarbonImmutable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Manufacturing\MRP\Infrastructure\Models\TimeShift;
use Modules\Equipment\ErrorMonitoring\Infrastructure\Models\EquipmentErrorLog;
use Modules\Equipment\ErrorMonitoring\Infrastructure\Models\OperatingTime;
use Modules\Masterdata\Equipment\Infrastructure\Models\Equipment;

final class IndexOperatingTimeAction
{
    use AsAction, HasApiResponse;

    public function asController(Request $request): array
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }

    private function isDataValid(Request $request, array $existingData): bool
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }

    // hiển thị dữ liệu
    private function getDataFromTable(Request $request)
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }

    // hàm lưu thời gian hoạt động vào database
    private function saveDataOperatingTime(array $results): void
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }

    public function calculateTimeShifts($request): array
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }

    public function calculateUptime(Equipment $equipment, $timeShifts, $start, $end, $errorLogs = [], $dateFormat)
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }

    private function calculateUnplannedStopTime(CarbonImmutable $shiftStart, CarbonImmutable $shiftEnd, $errorLogs): float
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }


    private function convertSecondsToHours($seconds): float
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }

    private function convertHoursToSeconds($hours): float
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }
}
