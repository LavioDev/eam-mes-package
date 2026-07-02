<?php

declare(strict_types=1);

namespace Modules\Equipment\Checklist\Domain\Actions\Resources;

use App\Concerns\HasApiResponse;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Equipment\Checklist\Infrastructure\Models\ChecklistSession;
use Modules\Equipment\Checklist\Domain\Events\Resources\ChecklistSessionDeleted;

final class DeleteChecklistSessionAction
{
    use HasApiResponse, AsAction;

    public function asController(string $id): JsonResponse
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }
}