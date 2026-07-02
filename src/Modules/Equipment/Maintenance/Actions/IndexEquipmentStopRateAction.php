<?php

declare(strict_types=1);

namespace Modules\Equipment\Maintenance\Actions;

use App\Concerns\HasApiResponse;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Equipment\Maintenance\Models\MaintenancePlan;
use Modules\Manufacturing\Lot\Infrastructure\Models\LotHistory;
use Modules\Manufacturing\Lot\Infrastructure\Models\Lot;
use Modules\Masterdata\Process\Infrastructure\Models\Process;
use Modules\Masterdata\Equipment\Infrastructure\Models\EquipmentError;
use Illuminate\Support\Collection;

final class IndexEquipmentStopRateAction
{

    use AsAction, HasApiResponse;

    public function asController(Request $request): JsonResponse
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }
}
