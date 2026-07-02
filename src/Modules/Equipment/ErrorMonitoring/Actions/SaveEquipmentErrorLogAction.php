<?php

declare(strict_types=1);

namespace Modules\Equipment\ErrorMonitoring\Actions;

use App\Concerns\HasApiResponse;
use App\Helpers\TranslateHelper;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Equipment\ErrorMonitoring\Models\EquipmentErrorLog;
use Modules\Equipment\ErrorMonitoring\Requests\SaveEquipmentErrorLogRequest;
use Modules\Masterdata\Equipment\Infrastructure\Models\EquipmentError;
use Throwable;

final class SaveEquipmentErrorLogAction
{

    use AsAction, HasApiResponse;

    /**
     * @throws Throwable
     */

    public function asController(SaveEquipmentErrorLogRequest $request): JsonResponse
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }
}
