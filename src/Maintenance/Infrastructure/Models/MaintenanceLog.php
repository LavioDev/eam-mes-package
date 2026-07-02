<?php

declare(strict_types=1);

namespace Modules\Equipment\Maintenance\Infrastructure\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Concerns\HasDefaultRouteBinding;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Core\File\Infrastructure\Models\ObjectFile;
use Modules\Equipment\Maintenance\Infrastructure\Factories\MaintenanceLogFactory;
use Modules\Masterdata\Equipment\Infrastructure\Models\Equipment;

/**
 * Class MaintenanceLog
 * @property string $id 
 * @property string $maintenance_item_id  
 * @property string $maintenance_schedule_id
 * @property string $type 
 * @property string $result 
 * @property string $note 
 * @property CarbonImmutable $log_date
 * @property CarbonImmutable $created_at
 * @property CarbonImmutable $updated_at
 */
final class MaintenanceLog extends Model
{
    use HasFactory, HasUuids, HasDefaultRouteBinding;

    protected $fillable = [
        'maintenance_item_id',
        'log_date',
        'note',
        'result',
        'type'
    ];

    protected $casts = [
        'log_date' => 'date',
    ];

    public function maintenanceSchedule(): BelongsTo
    {
        return $this->belongsTo(MaintenanceSchedule::class);
    }

    public function objectFiles()
    {
        return $this->morphMany(ObjectFile::class, 'target');
    }




    //     public function newEloquentBuilder($query): MaintenancePlanBuilder
    // {
    //     return new MaintenancePlanBuilder($query);
    // }

    protected static function newFactory(): MaintenanceLogFactory
    {
        return MaintenanceLogFactory::new();
    }
}
