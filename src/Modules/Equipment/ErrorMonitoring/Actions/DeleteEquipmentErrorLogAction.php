<?php

declare(strict_types=1);

namespace Modules\Equipment\ErrorMonitoring\Actions;

use App\Concerns\HasApiResponse;
use App\Helpers\TranslateHelper;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Equipment\ErrorMonitoring\Models\EquipmentErrorLog;
use Modules\Equipment\ErrorMonitoring\Domain\Events\Resources\EquipmentErrorLogDeleted;
use Throwable;

final class DeleteEquipmentErrorLogAction
{

    use HasApiResponse, AsAction;

    public function asController(string $id): JsonResponse
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }
}
