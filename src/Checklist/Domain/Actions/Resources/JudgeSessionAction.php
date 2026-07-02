<?php

declare(strict_types=1);

namespace Modules\Equipment\Checklist\Domain\Actions\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Equipment\Checklist\Infrastructure\Models\ChecklistDetail;
use Modules\Equipment\Checklist\Infrastructure\Models\ChecklistSession;
use Modules\Equipment\Checklist\Presentation\Requests\JudgeSessionRequest;
use Throwable;

final class JudgeSessionAction
{
    use AsAction;

    /**
     * @throws Throwable
     */
    public function asController(JudgeSessionRequest $request): JsonResponse
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }
}
