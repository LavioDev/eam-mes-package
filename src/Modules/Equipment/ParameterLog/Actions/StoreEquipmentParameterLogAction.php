<?php

declare(strict_types=1);

namespace Modules\Equipment\ParameterLog\Actions;

use App\Concerns\HasApiResponse;
use App\Helpers\TranslateHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Equipment\ParameterLog\Models\EquipmentParameterLog;
use Modules\Equipment\ParameterLog\Requests\StoreEquipmentParameterLogRequest;
use Throwable;
use Modules\Equipment\ParameterLog\Domain\Events\Resources\EquipmentParameterLogCreated;

final class StoreEquipmentParameterLogAction
{

    use HasApiResponse, AsAction;

    /**
     * @throws Throwable
     */

    public function asController(StoreEquipmentParameterLogRequest $request): JsonResponse
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }
}
