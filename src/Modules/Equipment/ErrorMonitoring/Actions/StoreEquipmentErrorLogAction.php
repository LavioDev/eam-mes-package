<?php

declare(strict_types=1);

namespace Modules\Equipment\ErrorMonitoring\Actions;

use App\Concerns\HasApiResponse;
use App\Helpers\TranslateHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Equipment\ErrorMonitoring\Models\EquipmentErrorLog;
use Modules\Equipment\ErrorMonitoring\Requests\StoreEquipmentErrorLogRequest;
use Throwable;
use Modules\Equipment\ErrorMonitoring\Domain\Events\Resources\EquipmentErrorLogCreated;

final class StoreEquipmentErrorLogAction
{

    use HasApiResponse, AsAction;

    /**
     * @throws Throwable
     */

    public function asController(StoreEquipmentErrorLogRequest $request): JsonResponse
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }
}
