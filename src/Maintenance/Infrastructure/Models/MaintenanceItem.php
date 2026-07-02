<?php

declare(strict_types=1);

namespace Modules\Equipment\Maintenance\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Equipment\Maintenance\Infrastructure\Builders\MaintenanceItemBuilder;
use Modules\Equipment\Maintenance\Infrastructure\Factories\MaintenanceItemFactory;

/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $maintenance_category_id
 * @property string $created_at
 * @property string $updated_at
 */
class MaintenanceItem extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'description',
        'maintenance_category_id'
    ];

    protected static function newFactory(): MaintenanceItemFactory
    {
        return MaintenanceItemFactory::new();
    }

    public function maintenanceCategory(): BelongsTo
    {
        return $this->belongsTo(MaintenanceCategory::class);
    }

    public function newEloquentBuilder($query): MaintenanceItemBuilder
    {
        return new MaintenanceItemBuilder($query);
    }
}
