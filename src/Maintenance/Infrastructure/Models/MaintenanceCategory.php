<?php

declare(strict_types=1);

namespace Modules\Equipment\Maintenance\Infrastructure\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Equipment\Maintenance\Infrastructure\Builders\MaintenancePlanBuilder;
use Modules\Equipment\Maintenance\Infrastructure\Factories\MaintenanceCategoryFactory;

/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $created_at
 * @property string $updated_at
 */
class MaintenanceCategory extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'description',
    ];

    public function maintenancePlans(): HasMany
    {
        return $this->hasMany(MaintenancePlan::class, 'maintenance_category_id', 'id');
    }

    protected static function newFactory(): MaintenanceCategoryFactory
    {
        return MaintenanceCategoryFactory::new();
    }


    public function newEloquentBuilder($query): MaintenancePlanBuilder
    {
        return new MaintenancePlanBuilder($query);
    }
}
