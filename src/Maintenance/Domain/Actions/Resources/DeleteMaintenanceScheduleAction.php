<?php

declare(strict_types=1);

namespace Modules\Equipment\Maintenance\Domain\Actions\Resources;

use App\Concerns\HasApiResponse;
use App\Helpers\TranslateHelper;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Equipment\Maintenance\Infrastructure\Models\MaintenanceSchedule;
use Modules\Equipment\Maintenance\Domain\Events\Resources\MaintenancePlanDeleted;
use Throwable;

final class DeleteMaintenanceScheduleAction
{
    use HasApiResponse, AsAction;

    public function asController(string $id): JsonResponse
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }

}