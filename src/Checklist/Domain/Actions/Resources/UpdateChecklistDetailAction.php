<?php

declare(strict_types=1);

namespace Modules\Equipment\Checklist\Domain\Actions\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Equipment\Checklist\Infrastructure\Models\ChecklistDetail;
use Modules\Equipment\Checklist\Infrastructure\Models\ChecklistSession;
use Modules\Equipment\Checklist\Presentation\Requests\UpdateChecklistDetailRequest;
use Modules\Masterdata\Checklist\Infrastructure\Models\Checklist;
use Throwable;

final class UpdateChecklistDetailAction
{
    use AsAction;

    /**
     * @throws Throwable
     */
    public function asController(UpdateChecklistDetailRequest $request): JsonResponse
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }
}
