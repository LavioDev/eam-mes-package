<?php

declare(strict_types=1);

namespace Modules\Equipment\MasterData\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class IotLog
 * @package Modules\Equipment\MasterData\Infrastructure\Models
 * @property int $id
 * @property CarbonImmutable $ts
 * @property string $type
 * @property array|null $data
 * @property string $equipment_id
 * @property string $work_center_id
 * @property string|null $work_order_id
 */
final class IotLog extends Model
{
    /**
     * Database connection name
     */
    protected $connection = 'pgsql_log';

    protected $fillable = [
        'ts',
        'type',
        'data',
        'equipment_id',
        'work_center_id',
        'work_order_id',
    ];

    public $incrementing = true;

    protected $keyType = 'int';

    protected $table = 'iot_logs';

    /**
     * Disable Laravel's automatic timestamp management
     */
    public $timestamps = false;

    /**
     * Manually define the created_at column name (we use 'ts' instead)
     */
    const CREATED_AT = 'ts';

    /**
     * We don't have an updated_at column
     */
    const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'ts' => 'immutable_datetime',
            'data' => 'array',
        ];
    }
}


