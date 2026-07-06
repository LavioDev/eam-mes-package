<?php

declare(strict_types=1);

namespace Modules\Equipment\MasterData\Actions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

final class ShowEquipmentErrorAction
{
    use AsAction;

    public function asController(Request $request): JsonResponse
    {
        return response()->json([]);
    }
}


