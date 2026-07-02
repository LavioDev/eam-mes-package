<?php

declare(strict_types=1);

namespace Modules\Equipment\Maintenance\Domain\Actions\Resources;

use App\Concerns\HasApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Equipment\Maintenance\Infrastructure\Models\MaintenancePlan;
use Modules\Equipment\Maintenance\Infrastructure\Models\MaintenanceSchedule;
use Throwable;
use Illuminate\Support\Facades\File;
use Modules\Core\File\Infrastructure\Models\ObjectFile;
use Modules\Equipment\Maintenance\Infrastructure\Models\MaintenanceLog;
use Modules\Equipment\Maintenance\Presentation\Requests\UpdateMaintenanceScheduleRequest;

final class UpdateMaintenanceScheduleAction
{
    use HasApiResponse, AsAction;

    /**
     * @throws Throwable
     */
    public function asController(string $id, UpdateMaintenanceScheduleRequest $request): JsonResponse
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }


}
