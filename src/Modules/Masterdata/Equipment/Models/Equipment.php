<?php

declare(strict_types=1);

namespace Modules\Masterdata\Equipment\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Equipment
 *
 * @property string $id
 * @property string|null $name
 * @property string $code
 * @property string $work_center_id
 * @property string|null $equipment_category_id
 * @property string|null $device_id
 * @property bool $is_active
 * @property-read EquipmentState|null $equipmentState
 * @property-read \Illuminate\Database\Eloquent\Collection|EquipmentImage[] $equipmentImages
 * @property CarbonImmutable $created_at
 * @property CarbonImmutable $updated_at
 */
final class Equipment extends Model
{
    use HasUuids;

    public $incrementing = false;

    protected $fillable = [
        'name',
        'code',
        'work_center_id',
        'equipment_category_id',
        'device_id',
        'is_active'
    ];

    protected $keyType = 'string';

    protected $table = 'eamo_equipment';

    /**
     * @return BelongsTo<EquipmentCategory, $this>
     */
    public function equipmentCategory(): BelongsTo
    {
        return $this->belongsTo(EquipmentCategory::class);
    }

    /**
     * @return HasMany<EquipmentParameter, $this>
     */
    public function equipmentParameters(): HasMany
    {
        return $this->hasMany(EquipmentParameter::class);
    }

    /**
     * @return BelongsToMany<EquipmentError, $this>
     */
    public function equipmentErrors(): BelongsToMany
    {
        return $this->belongsToMany(
            EquipmentError::class,
            'eamo_equipment_equipment_errors',
            'equipment_id',
            'equipment_error_id'
        )->withTimestamps();
    }

    /**
     * @return HasOne<EquipmentState, $this>
     */
    public function equipmentState(): HasOne
    {
        return $this->hasOne(EquipmentState::class);
    }

    /**
     * @return HasMany<EquipmentImage, $this>
     */
    public function equipmentImages(): HasMany
    {
        return $this->hasMany(EquipmentImage::class);
    }

    protected static function boot(): void
    {
        parent::boot();

        self::deleting(function (self $model) {
            $model->equipmentParameters()->delete();
            $model->equipmentState()->delete();
            $model->equipmentImages()->delete();
        });
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}

