<?php

declare(strict_types=1);

namespace Modules\Equipment\Checklist\Actions;

use App\Concerns\HasApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Equipment\Checklist\Models\ChecklistSession;
use Modules\Equipment\Checklist\Requests\UpdateChecklistSessionRequest;
use Throwable;
use Modules\Equipment\Checklist\Domain\Events\Resources\ChecklistSessionUpdated;

final class UpdateChecklistSessionAction
{

    use HasApiResponse, AsAction;

    /**
     * @throws Throwable
     */

    public function asController(string $id, UpdateChecklistSessionRequest $request): JsonResponse
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }
}
