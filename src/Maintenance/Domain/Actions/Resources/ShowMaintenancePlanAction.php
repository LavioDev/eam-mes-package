<?php

declare(strict_types=1);

namespace Modules\Equipment\Maintenance\Domain\Actions\Resources;

use App\Concerns\HasApiResponse;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Equipment\Maintenance\Infrastructure\Models\MaintenancePlan;
use Throwable;

final class ShowMaintenancePlanAction
{
    use HasApiResponse, AsAction;

    public function asController(string $id): JsonResponse
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }
}