<?php

declare(strict_types=1);

namespace Modules\Equipment\Maintenance\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    protected $table = 'eamo_maintenance_items';

    use HasUuids;

    protected $fillable = [
        'name',
        'description',
        'maintenance_category_id'
    ];



    public function maintenanceCategory(): BelongsTo
    {
        return $this->belongsTo(MaintenanceCategory::class);
    }


}
