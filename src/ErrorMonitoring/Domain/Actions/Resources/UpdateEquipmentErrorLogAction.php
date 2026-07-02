<?php

declare(strict_types=1);

namespace Modules\Equipment\ErrorMonitoring\Domain\Actions\Resources;

use App\Concerns\HasApiResponse;
use App\Helpers\TranslateHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Equipment\ErrorMonitoring\Infrastructure\Models\EquipmentErrorLog;
use Modules\Equipment\ErrorMonitoring\Presentation\Requests\UpdateEquipmentErrorLogRequest;
use Throwable;
use Modules\Equipment\ErrorMonitoring\Domain\Events\Resources\EquipmentErrorLogUpdated;

final class UpdateEquipmentErrorLogAction
{
    use HasApiResponse, AsAction;

    /**
     * @throws Throwable
     */
    public function asController(string $id, UpdateEquipmentErrorLogRequest $request): JsonResponse
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }
} 