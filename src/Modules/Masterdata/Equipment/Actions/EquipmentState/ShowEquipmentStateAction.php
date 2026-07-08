<?php

declare(strict_types=1);

namespace Modules\Masterdata\Equipment\Actions\EquipmentState;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

final class ShowEquipmentStateAction
{
    use AsAction;

    public function asController(Request $request): JsonResponse
    {
        return response()->json([]);
    }
}
