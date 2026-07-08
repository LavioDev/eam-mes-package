<?php

declare(strict_types=1);

namespace Modules\Equipment\ErrorMonitoring\Actions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Equipment\ErrorMonitoring\Models\OperatingTime;

final class UpdateOperatingTimeAction
{
    use AsAction;

    public function asController(string $id, Request $request): JsonResponse
    {
        // TODO: Implement custom logic
        return response()->json([]);
    }
}
