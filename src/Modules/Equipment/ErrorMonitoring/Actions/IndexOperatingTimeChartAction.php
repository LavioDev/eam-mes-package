<?php

declare(strict_types=1);

namespace Modules\Equipment\ErrorMonitoring\Actions;

use App\Concerns\HasApiResponse;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Masterdata\Equipment\Domain\DTOs\OperatingTimeChartDTO;
use Modules\Equipment\ErrorMonitoring\Models\OperatingTime;

final class IndexOperatingTimeChartAction
{

    use AsAction, HasApiResponse;

    public function asController(Request $request): array
     {
        // TODO: Implement custom logic
        return response()->json([]);
    }
}
