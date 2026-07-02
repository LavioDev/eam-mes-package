<?php

declare(strict_types=1);

namespace Modules\Equipment\ParameterLog\Actions;

use App\Concerns\HasApiResponse;
use App\Helpers\TranslateHelper;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Equipment\ParameterLog\Domain\Events\Resources\EquipmentParameterLogDeleted;
use Modules\Equipment\ParameterLog\Models\EquipmentParameterLog;
use Throwable;

final class DeleteEquipmentParameterLogAction
{

    use AsAction, HasApiResponse;

    public function asController(string $id): JsonResponse
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }
}
