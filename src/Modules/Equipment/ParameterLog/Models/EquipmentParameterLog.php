<?php

declare(strict_types=1);

namespace Modules\Equipment\ParameterLog\Models;

use App\Concerns\HasDefaultRouteBinding;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\User\Infrastructure\Models\User;
use Modules\Masterdata\Equipment\Models\Equipment;
use Modules\Masterdata\Equipment\Models\EquipmentParameter;
use Modules\Masterdata\Equipment\Models\Unit;

/**
 * Class EquipmentParameterLog
 * @package Modules\Equipment\ParameterLog\Models
 * @property string $id
 * @property string $equipment_id
 * @property string $equipment_parameter_id
 * @property string|null $unit_id
 * @property string|null $value
 * @property string|null $user_id
 * @property CarbonImmutable|null $recorded_at
 * @property-read Equipment $equipment
 * @property-read EquipmentParameter $parameter
 * @property-read Unit|null $unit
 * @property-read User|null $user
 * @property CarbonImmutable $created_at
 * @property CarbonImmutable $updated_at
 * @property CarbonImmutable|null $deleted_at
 */
final class EquipmentParameterLog extends Model
{
    use HasUuids, HasDefaultRouteBinding, SoftDeletes;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $table = 'eamo_equipment_parameter_logs';

    protected $fillable = [
        'equipment_id',
        'equipment_parameter_id',
        'unit_id',
        'value',
        'user_id',
        'recorded_at',
    ];

    /**
     * @return BelongsTo<Equipment, $this>
     */
    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    /**
     * @return BelongsTo<EquipmentParameter, $this>
     */
    public function parameter(): BelongsTo
    {
        return $this->belongsTo(EquipmentParameter::class);
    }

    /**
     * @return BelongsTo<Unit, $this>
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    /**
     * @return BelongsTo<EquipmentParameter, $this>
     */
    public function equipmentParameter(): BelongsTo
    {
        return $this->belongsTo(EquipmentParameter::class, 'equipment_parameter_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected function casts(): array
    {
        return [
            'recorded_at' => 'immutable_datetime',
        ];
    }
}
