<?php

declare(strict_types=1);

namespace Modules\Masterdata\Equipment\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class EquipmentError
 *
 * @property string $id
 * @property string $name
 * @property string|null $reason
 * @property string|null $fix
 * @property string|null $protection_measures
 * @property CarbonImmutable $created_at
 * @property CarbonImmutable $updated_at
 */
final class EquipmentError extends Model
{
    use HasUuids;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'reason',
        'fix',
        'protection_measures',
    ];

    protected $keyType = 'string';

    protected $table = 'eamo_equipment_errors';

    private array $pendingEquipmentIds = [];

    /**
     * @return BelongsToMany<Equipment, $this>
     */
    public function equipment(): BelongsToMany
    {
        return $this->belongsToMany(
            Equipment::class,
            'eamo_equipment_error_logs',
            'equipment_error_id',
            'equipment_id'
        )->wherePivotNull('occurred_at')->withTimestamps();
    }

    public function errorLogs(): HasMany
    {
        return $this->hasMany(\Modules\Equipment\ErrorMonitoring\Models\EquipmentErrorLog::class, 'equipment_error_id');
    }

    public function setPendingEquipmentIds(array $equipmentIds): void
    {
        $filtered = array_values(array_filter($equipmentIds));
        $this->pendingEquipmentIds = $filtered;
        if ($filtered !== []) {
            $this->forceFill(['equipment_ids' => $filtered]);
        }
    }

    public function getPendingEquipmentIds(): array
    {
        return $this->pendingEquipmentIds;
    }

    public function getEquipmentIdAttribute(): ?string
    {
        if ($this->relationLoaded('equipment')) {
            return $this->getRelation('equipment')->first()?->id;
        }

        return $this->equipment()->limit(1)->pluck('id')->first();
    }

    protected static function booted()
    {
        self::saving(function (self $model): void {
            if (array_key_exists('equipment_ids', $model->attributes)) {
                unset($model->attributes['equipment_ids']);
            }
        });
    }

    protected function casts(): array
    {
        return [];
    }
}

