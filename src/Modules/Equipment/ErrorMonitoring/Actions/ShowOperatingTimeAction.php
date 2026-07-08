<?php

declare(strict_types=1);

namespace Modules\Equipment\ErrorMonitoring\Actions;

use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Equipment\ErrorMonitoring\Models\OperatingTime;

final class ShowOperatingTimeAction
{
    use AsAction;

    public function asController(string $id): JsonResponse
    {
        // TODO: Implement custom logic
        return response()->json([]);
    }
}
