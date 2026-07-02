<?php

declare(strict_types=1);

namespace Modules\Equipment\Maintenance\Domain\Actions\Resources;

use App\Concerns\HasApiResponse;
use App\Helpers\TranslateHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Equipment\Maintenance\Infrastructure\Models\MaintenancePlan;
use Modules\Equipment\Maintenance\Presentation\Requests\StoreMaintenancePlanRequest;
use Throwable;
use Carbon\Carbon;

final class StoreMaintenancePlanAction
{
    use HasApiResponse, AsAction;

    public function asController(StoreMaintenancePlanRequest $request): JsonResponse
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }

    public function handle(array $input): array
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }

}
