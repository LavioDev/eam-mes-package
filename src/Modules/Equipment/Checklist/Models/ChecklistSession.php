<?php

declare(strict_types=1);

namespace Modules\Equipment\Checklist\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Concerns\HasDefaultRouteBinding;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Modules\Masterdata\Equipment\Infrastructure\Models\Equipment;
use Modules\Equipment\Checklist\Models\ChecklistDetail;

/**
 * @property string $id
 * @property string $equipment_id
 * @property string $session_date
 * @property string $created_by
 * @property string $created_at
 * @property string $updated_at
 */
final class ChecklistSession extends Model
{
    use HasUuids, HasDefaultRouteBinding;

    protected $table = 'eamo_checklist_sessions';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'session_date',
        'created_by',
        'equipment_id'
    ];

    protected $casts = [
        'session_date' => 'date',
    ];

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class, 'equipment_id');
    }



    public function details()
    {
        return $this->hasMany(ChecklistDetail::class, 'session_id');
    }
}
