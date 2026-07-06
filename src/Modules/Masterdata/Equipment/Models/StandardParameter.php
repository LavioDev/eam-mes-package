<?php

declare(strict_types=1);

namespace Modules\Masterdata\Equipment\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class StandardParameter
 * @package Modules\Masterdata\Equipment\Infrastructure\Models
 * @property string $id
 * @property string $equipment_id
 * @property string $equipment_parameter_id
 * @property float $standard
 * @property float $standard_max
 * @property float $standard_min
 * @property string|null $unit_id
 * @property-read Equipment $equipment
 * @property-read EquipmentParameter $equipmentParameter
 * @property CarbonImmutable $created_at
 * @property CarbonImmutable $updated_at
 */
final class StandardParameter extends Model
{
    use HasUuids;

    protected $fillable = [
        'id',
        'equipment_id',
        'equipment_parameter_id',
        'standard',
        'standard_max',
        'standard_min',
        'unit_id',
    ];

    public $incrementing = false;

    protected $keyType = 'string';

    protected $table = 'standard_parameters';

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    public function equipmentParameter(): BelongsTo
    {
        return $this->belongsTo(EquipmentParameter::class);
    }
}

