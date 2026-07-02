<?php

declare(strict_types=1);

namespace Modules\Equipment\ParameterLog\Actions;

use App\Concerns\HasApiResponse;
use App\Helpers\TranslateHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Equipment\ParameterLog\Models\EquipmentParameterLog;
use Modules\Equipment\ParameterLog\Requests\UpdateEquipmentParameterLogRequest;
use Throwable;
use Modules\Equipment\ParameterLog\Domain\Events\Resources\EquipmentParameterLogUpdated;

final class UpdateEquipmentParameterLogAction
{

    use HasApiResponse, AsAction;

    /**
     * @throws Throwable
     */

    public function asController(string $id, UpdateEquipmentParameterLogRequest $request): JsonResponse
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }
}
