<?php

declare(strict_types=1);

namespace Modules\Equipment\Maintenance\Actions;

use App\Concerns\HasApiResponse;
use App\Helpers\TranslateHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Equipment\Maintenance\Models\MaintenanceCategory;
use Modules\Equipment\Maintenance\Requests\UpdateMaintenanceCategoryRequest;
use Throwable;

final class UpdateMaintenanceCategoryAction
{

    use HasApiResponse, AsAction;

    /**
     * @throws Throwable
     */

    public function asController(string $id, UpdateMaintenanceCategoryRequest $request): JsonResponse
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }
}
