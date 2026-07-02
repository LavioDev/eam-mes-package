<?php

declare(strict_types=1);

namespace Modules\Equipment\Maintenance\Domain\Actions\Resources;

use App\Concerns\HasApiResponse;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Equipment\Maintenance\Infrastructure\Models\MaintenancePlan;
use Modules\Equipment\Maintenance\Infrastructure\Models\MaintenanceSchedule;
use Modules\Equipment\Maintenance\Presentation\Requests\UpdateMaintenancePlanRequest;
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
