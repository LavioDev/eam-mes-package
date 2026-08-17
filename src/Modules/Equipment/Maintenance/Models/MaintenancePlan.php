<?php

declare(strict_types=1);

namespace Modules\Equipment\Maintenance\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Date;
use Modules\Core\User\Infrastructure\Models\User;
use Modules\Masterdata\Equipment\Models\Equipment;

/**
 * Class MaintenancePlan
 * @package Modules\Equipment\Maintenance\Models
 * @property string $id 
 * @property string $plan_code 
 * @property string $maintenance_type 
 * @property string $equipment_id 
 * @property string $user_id 
 * @property string $notes 
 * @property string $maintenance_category_id
 * @property CarbonImmutable|null $date
 * @property CarbonImmutable|null $start_time
 * @property CarbonImmutable|null $end_time
 * @property CarbonImmutable|null $actual_start_time
 * @property CarbonImmutable|null $actual_end_time
 * @property string|null $cycle_type
 * @property int $cycle_interval 
 * @property string $created_at
 * @property string $updated_at
 * @method static MaintenancePlanBuilder query()
 */
final class MaintenancePlan extends Model
{
    // protected static function boot(): void
    // {
    //     parent::boot();

    //     self::creating(function (self $model) {
    //         if (empty($model->occurred_at)) {
    //             $model->occurred_at = Date::now();
    //         }
    //     });
    // }

    use HasUuids;
        
    protected $fillable = [
        'equipment_id',
        'date',
        'plan_code',
        'start_time',
        'actual_start_time',
        'end_time',
        'actual_end_time',
        'cycle_type',
        'cycle_interval',
        'notes',
        'maintenance_type',
        'user_id'
    ];

    protected $casts = [
        // 'occurred_at' => 'immutable_datetime',
        // 'restarted_at' => 'immutable_datetime',
        // 'handled_at' => 'immutable_datetime',
    ];

    /**
     * @return BelongsTo<Equipment, $this>
     */
    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function maintenanceSchedules(): HasMany
    {
        return $this->hasMany(MaintenanceSchedule::class);
    }

    public function maintenanceCategory(): BelongsTo
    {
        return $this->belongsTo(MaintenanceCategory::class, 'maintenance_category_id');
    }

    public $incrementing = false;

    protected $keyType = 'string';

    protected $table = 'eamo_maintenance_plans';




}
