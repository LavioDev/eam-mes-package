<?php

declare(strict_types=1);

namespace Modules\Equipment\Maintenance\Domain\Actions\Resources;

use App\Concerns\HasApiResponse;
use App\Helpers\TranslateHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Equipment\Maintenance\Infrastructure\Models\MaintenanceCategory;
use Modules\Equipment\Maintenance\Presentation\Requests\UpdateMaintenanceCategoryRequest;
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