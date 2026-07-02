<?php

declare(strict_types=1);

namespace Modules\Equipment\ErrorMonitoring\Models;

use App\Concerns\HasDefaultRouteBinding;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\User\Infrastructure\Models\User;
use Modules\Masterdata\Equipment\Infrastructure\Models\Equipment;
use Modules\Masterdata\Equipment\Infrastructure\Models\EquipmentError;

/**
 * Class EquipmentErrorLog
 *
 * @property string $id
 * @property string $equipment_id
 * @property string|null $equipment_error_id
 * @property CarbonImmutable $occurred_at
 * @property CarbonImmutable|null $restarted_at
 * @property CarbonImmutable|null $handled_at
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read CarbonImmutable|null $handled_time
 * @property string|null $handler_id
 * @property-read Equipment $equipment
 * @property-read EquipmentError|null $equipmentError
 * @property-read User|null $handler
 *
 * @method static EquipmentErrorLogBuilder query()
 */
final class EquipmentErrorLog extends Model
{
    use HasDefaultRouteBinding, HasUuids;

    public const MAX_LOG_RECORDS = 2000000;

    public const MINIMUM_SECONDS_FOR_REAL_ERROR = 60;

        
    public $timestamps = false;

    public $incrementing = false;

    protected $appends = [
        'handled_time',
    ];

    protected $fillable = [
        'equipment_id',
        'equipment_error_id',
        'occurred_at', // Thời điểm lỗi phát sinh
        'restarted_at', // Thời điểm thiết bị chạy lại
        'handled_at', // Thời điểm xử lý xong lỗi
        'handler_id', // ID của người xử lý lỗi
    ];

    protected $keyType = 'string';

    protected $table = 'eamo_equipment_error_logs';

    public static function pruneExcess(): void
    {
        $equipmentIds = self::query()
            ->select('equipment_id')
            ->groupBy('equipment_id')
            ->havingRaw('COUNT(*) > ?', [self::MAX_LOG_RECORDS])
            ->pluck('equipment_id');

        foreach ($equipmentIds as $equipmentId) {
            $cutoff = self::query()
                ->where('equipment_id', $equipmentId)
                ->select(['occurred_at', 'id'])
                ->orderBy('occurred_at', 'desc')
                ->orderBy('id', 'desc')
                ->skip(self::MAX_LOG_RECORDS - 1)
                ->first();

            if (! $cutoff) {
                continue;
            }

            $cutoffOccurredAt = $cutoff->occurred_at;
            $cutoffId = $cutoff->id;

            self::query()
                ->where('equipment_id', $equipmentId)
                ->where(function (Builder $query) use ($cutoffOccurredAt, $cutoffId): void {
                    $query
                        ->where('occurred_at', '<', $cutoffOccurredAt)
                        ->orWhere(function (Builder $query) use ($cutoffOccurredAt, $cutoffId): void {
                            $query
                                ->where('occurred_at', $cutoffOccurredAt)
                                ->where('id', '<', $cutoffId);
                        });
                })
                ->delete();
        }
    }

    public function handledTime(): Attribute
    {
        return Attribute::get(
            fn() => $this->handled_at && $this->occurred_at
                ? $this->occurred_at->diffInSeconds($this->handled_at)
                : null
        );
    }

    /**
     * @return BelongsTo<Equipment, $this>
     */
    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class, 'equipment_id', 'id');
    }

    /**
     * @return BelongsTo<EquipmentError, $this>
     */
    public function equipmentError(): BelongsTo
    {
        return $this->belongsTo(EquipmentError::class, 'equipment_error_id', 'id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handler_id', 'id');
    }



    protected static function boot(): void
    {
        parent::boot();

        self::creating(function (self $model) {
            if (empty($model->occurred_at)) {
                $model->occurred_at = CarbonImmutable::now();
            }
        });
    }



    protected function casts(): array
    {
        return [
            'occurred_at' => 'immutable_datetime',
            'restarted_at' => 'immutable_datetime',
            'handled_at' => 'immutable_datetime',
        ];
    }
}
