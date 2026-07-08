<?php

declare(strict_types=1);

namespace Modules\Equipment\Maintenance\Actions;

use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Equipment\Maintenance\Models\MaintenanceSchedule;

final class ShowMaintenanceScheduleAction
{
    use AsAction;

    public function asController(string $id): JsonResponse
    {
        // TODO: Implement custom logic
        return response()->json([]);
    }
}
