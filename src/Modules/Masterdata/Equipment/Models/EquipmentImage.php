<?php

declare(strict_types=1);

namespace Modules\Masterdata\Equipment\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class EquipmentImage
 *
 * @property string $id
 * @property string $equipment_id
 * @property string $image_id
 * @property string|null $path
 * @property-read Equipment $equipment
 * @property CarbonImmutable $created_at
 * @property CarbonImmutable $updated_at
 */
final class EquipmentImage extends Model
{
    use HasUuids;

    public $incrementing = false;

    protected $fillable = [
        'equipment_id',
        'image_id',
        'path',
    ];

    protected $keyType = 'string';

    protected $table = 'eamo_equipment_images';

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }
}
