<?php

declare(strict_types=1);

namespace Modules\Equipment\Maintenance\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $created_at
 * @property string $updated_at
 */
class MaintenanceCategory extends Model
{
    protected $table = 'eamo_maintenance_categories';

    use HasUuids;

    protected $fillable = [
        'name',
        'description',
    ];

    public function maintenancePlans(): HasMany
    {
        return $this->hasMany(MaintenancePlan::class, 'maintenance_category_id', 'id');
    }





}
