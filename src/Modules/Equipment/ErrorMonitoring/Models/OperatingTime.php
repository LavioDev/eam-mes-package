<?php

declare(strict_types=1);

namespace Modules\Equipment\ErrorMonitoring\Models;

use App\Concerns\HasDefaultRouteBinding;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OperatingTime
 *
 * @property string $id
 * @property string $equipment_id
 * @property string|null $equipment_name
 * @property float $working_time
 * @property float $planned_stop_time
 * @property float $unplanned_stop_time
 * @property float $planned_operating_time
 * @property float $actual_operating_time
 * @property float $availability_factor
 * @property CarbonImmutable $start_time
 * @property CarbonImmutable $end_time
 * @property CarbonImmutable|null $date
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 *
 * @property-read Equipment $equipment
 *
 */
final class OperatingTime extends Model
{

    use HasDefaultRouteBinding, HasUuids;

    protected $fillable = [

        'equipment_id',
        'equipment_name',
        'working_time',
        'planned_stop_time',
        'unplanned_stop_time',
        'planned_operating_time',
        'actual_operating_time',
        'availability_factor',
        'start_time',
        'end_time',
        'date',
    ];

    protected $table = 'eamo_operating_times';

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];
}
