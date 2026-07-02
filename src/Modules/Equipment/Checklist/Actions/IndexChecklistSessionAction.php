<?php

declare(strict_types=1);

namespace Modules\Equipment\Checklist\Actions;

use App\Concerns\HasApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Equipment\Checklist\Models\ChecklistSession;
use Throwable;
use Illuminate\Support\Carbon;

final class IndexChecklistSessionAction
{

    use HasApiResponse, AsAction;

    public function asController(Request $request): JsonResponse
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }
}
