<?php

declare(strict_types=1);

namespace Modules\Masterdata\Equipment\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EquipmentCategory
 *
 * @property string $id
 * @property string $name
 * @property CarbonImmutable $created_at
 * @property CarbonImmutable $updated_at
 */
final class EquipmentCategory extends Model
{
    use HasUuids;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
    ];

    protected $keyType = 'string';

    protected $table = 'eamo_equipment_categories';

    protected function casts(): array
    {
        return [];
    }
}

