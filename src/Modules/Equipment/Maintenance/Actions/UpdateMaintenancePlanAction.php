<?php

declare(strict_types=1);

namespace Modules\Equipment\Maintenance\Actions;

use App\Concerns\HasApiResponse;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Equipment\Maintenance\Models\MaintenancePlan;
use Modules\Equipment\Maintenance\Models\MaintenanceSchedule;
use Modules\Equipment\Maintenance\Requests\UpdateMaintenancePlanRequest;
use Throwable;

final class UpdateMaintenancePlanAction
{

    use AsAction, HasApiResponse;

    /**
     * @throws Throwable
     */

    public function asController(string $id, UpdateMaintenancePlanRequest $fields): JsonResponse
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }
}
