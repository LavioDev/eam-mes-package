<?php

declare(strict_types=1);

namespace Modules\Equipment\ParameterLog\Actions;

use App\Helpers\TranslateHelper;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Equipment\ParameterLog\Domain\Events\Resources\EquipmentParameterLogCreated;
use Modules\Equipment\ParameterLog\Models\EquipmentParameterLog;
use Modules\Equipment\ParameterLog\Requests\SaveEquipmentParameterLogRequest;
use Modules\Manufacturing\Tracking\Infrastructure\Models\WorkCenterTracker;
use Throwable;

final class SaveEquipmentParameterLogAction
{

    use AsAction;public function asController(SaveEquipmentParameterLogRequest $request)
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }
}
