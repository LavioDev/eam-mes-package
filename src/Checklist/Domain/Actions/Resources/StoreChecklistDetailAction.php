<?php

declare(strict_types=1);

namespace Modules\Equipment\Checklist\Domain\Actions\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Masterdata\Checklist\Infrastructure\Models\Checklist;
use Modules\Equipment\Checklist\Infrastructure\Models\ChecklistDetail;
use Modules\Equipment\Checklist\Infrastructure\Models\ChecklistSession;
use Modules\Equipment\Checklist\Presentation\Requests\StoreChecklistDetailRequest;
use Throwable;
use App\Helpers\TranslateHelper;
use App\Concerns\HasApiResponse;
use Illuminate\Support\Str;

final class StoreChecklistDetailAction
{
    use HasApiResponse, AsAction;

    /**
     * @throws Throwable
     */
    public function asController(StoreChecklistDetailRequest $request): JsonResponse
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }
}
