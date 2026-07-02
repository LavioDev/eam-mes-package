<?php

declare(strict_types=1);

namespace Modules\Equipment\Checklist\Actions;

use App\Concerns\HasApiResponse;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Equipment\Checklist\Models\ChecklistSession;
use Modules\Equipment\Checklist\Domain\Events\Resources\ChecklistDetailDeleted;

final class DeleteChecklistDetailAction
{

    use HasApiResponse, AsAction;

    public function asController(string $id): JsonResponse
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }
}
