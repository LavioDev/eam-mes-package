<?php

declare(strict_types=1);

namespace Modules\Equipment\Checklist\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Masterdata\Checklist\Infrastructure\Models\Checklist;
use Modules\Equipment\Checklist\Infrastructure\Builders\ChecklistDetailBuilder;
use App\Concerns\HasDefaultRouteBinding;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * @property string $id
 * @property string $checklist_id
 * @property string $session_id
 * @property string $description
 * @property string $result
 * @property string $created_at
 * @property string $updated_at
 */
final class ChecklistDetail extends Model
{
    use HasUuids, HasDefaultRouteBinding;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'checklist_details';

    protected $fillable = [
        'checklist_id',
        'session_id',
        'description',
        'result'
    ];

    public function checklist(): BelongsTo
    {
        return $this->belongsTo(Checklist::class, 'checklist_id');
    }
    public function session()
    {
        return $this->belongsTo(ChecklistSession::class, 'session_id'); // ✅ CORRECT
    }

    public function newEloquentBuilder($query): ChecklistDetailBuilder
    {
        return new ChecklistDetailBuilder($query);
    }
}
