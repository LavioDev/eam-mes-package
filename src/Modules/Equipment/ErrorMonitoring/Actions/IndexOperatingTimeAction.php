<?php

declare(strict_types=1);

namespace Modules\Equipment\ErrorMonitoring\Actions;

use App\Concerns\HasApiResponse;
use Carbon\CarbonImmutable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Manufacturing\MRP\Infrastructure\Models\TimeShift;
use Modules\Equipment\ErrorMonitoring\Models\EquipmentErrorLog;
use Modules\Equipment\ErrorMonitoring\Models\OperatingTime;
use Modules\Masterdata\Equipment\Infrastructure\Models\Equipment;

final class IndexOperatingTimeAction
{

    use AsAction, HasApiResponse;

    public function asController(Request $request): array
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }
}
