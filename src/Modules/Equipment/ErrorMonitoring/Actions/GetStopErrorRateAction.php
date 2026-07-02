<?php

namespace Modules\Equipment\ErrorMonitoring\Actions;

use App\Concerns\HasApiResponse;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Masterdata\Equipment\Infrastructure\Models\EquipmentError;

final readonly class GetStopErrorRateAction
{

    use AsAction, HasApiResponse;

    public function asController(): JsonResponse
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }
}
