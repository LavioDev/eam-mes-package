<?php

declare(strict_types=1);

namespace Modules\Equipment\ErrorMonitoring\Domain\Actions\Resources;

use App\Concerns\HasApiResponse;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Masterdata\Equipment\Domain\DTOs\OperatingTimeChartDTO;
use Modules\Equipment\ErrorMonitoring\Infrastructure\Models\OperatingTime;

final class IndexOperatingTimeChartAction
{
    use AsAction, HasApiResponse;

    public function asController(Request $request): array
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }

    private function getDataFromTable(Request $request)
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }

    private function sumOperatingTimes(array $data): array
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }

    private function convertSecondsToHours($seconds): float
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }
}
