<?php

declare(strict_types=1);

namespace Modules\Equipment\Maintenance\Domain\Actions\Resources;

use App\Concerns\HasApiResponse;
use App\Helpers\TranslateHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Equipment\Maintenance\Infrastructure\Models\MaintenanceCategory;
use Modules\Equipment\Maintenance\Presentation\Requests\StoreMaintenanceCategoryRequest;
use Modules\Masterdata\Product\Infrastructure\Models\Product;
use Modules\Masterdata\Product\Presentation\Requests\StoreProductRequest;
use Modules\Masterdata\Unit\Infrastructure\Models\Unit;
use Throwable;
use Modules\Masterdata\Product\Domain\Events\Resources\ProductCreated;

final class StoreMaintenanceCategoryAction
{
    use HasApiResponse, AsAction;

    /**
     * @throws Throwable
     */
    public function asController(StoreMaintenanceCategoryRequest $request): JsonResponse
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }
}