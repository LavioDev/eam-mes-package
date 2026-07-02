<?php

declare(strict_types=1);

namespace Modules\Equipment\Maintenance\Infrastructure\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Concerns\HasDefaultRouteBinding;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Masterdata\Equipment\Infrastructure\Models\Equipment;

/**
 * Class MaintenanceSchedule
 * @property string $id
 * @property string $equipment_id
 * @property string $maintenance_item_id
 * @property string $maintenance_plan_id
 * @property CarbonImmutable $date
 * @property CarbonImmutable $created_at
 * @property CarbonImmutable $updated_at
 * @method static MaintenanceScheduleBuilder query()
 */
final class MaintenanceSchedule extends Model
{
    protected $table = 'eamo_maintenance_schedules';

    use HasUuids, HasDefaultRouteBinding;

    protected $fillable = [
        'equipment_id',
        'maintenance_item_id',
        'maintenance_plan_id',
        'date',
    ];

    protected $casts = [
    ];

    public function maintenancePlan(): BelongsTo
    {
        return $this->belongsTo(MaintenancePlan::class);
    }

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    public function maintenanceItem(): BelongsTo
    {
        return $this->belongsTo(MaintenanceItem::class);
    }

    // public function maintenanceLog(): HasOne
    // {
    //     return $this->hasOne(MaintenanceLog::class, 'maintenance_schedule_id', 'id');
    // }

    public function maintenanceLogs(): HasMany
    {
        return $this->hasMany(MaintenanceLog::class, 'maintenance_schedule_id');
    }




}
