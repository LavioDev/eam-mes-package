<?php

declare(strict_types=1);

namespace Modules\Equipment\ParameterLog\Domain\Actions\Resources;

use App\Concerns\HasApiResponse;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Equipment\ParameterLog\Infrastructure\Models\EquipmentParameterLog;
use Throwable;

final class ShowEquipmentParameterLogAction
{
    use HasApiResponse, AsAction;

    public function asController(string $id): JsonResponse
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }
}
