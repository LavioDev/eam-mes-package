<?php

declare(strict_types=1);

namespace Modules\Equipment\ParameterLog\Actions;

use App\Concerns\HasApiResponse;
use Carbon\CarbonImmutable;
use Modules\Equipment\ParameterLog\Models\EquipmentErrorLog;
use Modules\Manufacturing\MRP\Infrastructure\Models\TimeShift;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Equipment\ParameterLog\Actions\IndexOperatingTimeAction;
use Modules\Masterdata\Equipment\Infrastructure\Models\Equipment;

final class OverviewEquipmentParameterLogAction
{

    use AsAction, HasApiResponse;

    public function asController(Request $request, string $equipmentId): JsonResponse
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }
}
