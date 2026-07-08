<?php

declare(strict_types=1);

namespace Modules\Masterdata\Equipment\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class EquipmentParameter
 *
 * @property string $id
 * @property string $equipment_id
 * @property string|null $unit_id
 * @property string $code
 * @property-read Equipment $equipment
 * @property-read StandardParameter|null $standardParameter
 * @property CarbonImmutable $created_at
 * @property CarbonImmutable $updated_at
 */
final class EquipmentParameter extends Model
{
    use HasUuids;

    public $incrementing = false;

    protected $fillable = [
        'equipment_id',
        'unit_id',
        'code',
    ];

    protected $keyType = 'string';

    protected $table = 'eamo_equipment_parameters';

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    public function standardParameter(): HasOne
    {
        return $this->hasOne(StandardParameter::class);
    }

    protected function casts(): array
    {
        return [];
    }
}

