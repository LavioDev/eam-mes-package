<?php

declare(strict_types=1);

namespace Modules\Equipment\ParameterLog\Domain\Actions\Resources;

use App\Concerns\HasApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Equipment\ParameterLog\Infrastructure\Models\EquipmentParameterLog;
use Modules\Masterdata\Equipment\Infrastructure\Models\StandardParameter;
use Throwable;

final class IndexEquipmentParameterLogAction
{
    use AsAction, HasApiResponse;

    public function asController(Request $request): JsonResponse
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }

    /**
     * Lấy thông tin standardParameter qua equipmentParameter ID
     */
    public function getStandardParameterByEquipmentParameterId(string $equipmentParameterId): ?StandardParameter
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }
}
