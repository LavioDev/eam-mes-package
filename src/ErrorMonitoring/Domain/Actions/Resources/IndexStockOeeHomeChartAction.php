<?php

declare(strict_types=1);

namespace Modules\Equipment\ErrorMonitoring\Domain\Actions\Resources;

use App\Concerns\HasApiResponse;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Manufacturing\Statistics\Domain\Actions\GetOeeAction;
use Modules\Manufacturing\Statistics\Presentation\Requests\GetOeeRequest;

final class IndexStockOeeHomeChartAction
{
    use AsAction, HasApiResponse;

    public function asController(GetOeeRequest $request): JsonResponse
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }
}
