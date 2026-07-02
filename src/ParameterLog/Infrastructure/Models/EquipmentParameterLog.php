<?php

declare(strict_types=1);

namespace Modules\Equipment\ParameterLog\Infrastructure\Models;

use App\Concerns\HasDefaultRouteBinding;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Masterdata\Unit\Infrastructure\Models\Unit;
use Modules\Masterdata\Equipment\Infrastructure\Models\EquipmentParameter;
use Modules\Masterdata\Equipment\Infrastructure\Models\Equipment;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Inventory\Lot\Infrastructure\Models\StockProductionLot;
use Modules\Manufacturing\Lot\Infrastructure\Models\Lot;
use Modules\Masterdata\Product\Infrastructure\Models\Product;

/**
 * Class EquipmentParameterLog
 * @package Modules\Equipment\ParameterLog\Infrastructure\Models
 * @property string $id
 * @property string $equipment_id
 * @property string $equipment_parameter_id
 * @property string|null $product_id
 * @property string|null $lot_id
 * @property string|null $unit_id
 * @property string|null $component_id
 * @property string $value
 * @property-read Equipment $equipment
 * @property-read EquipmentParameter $parameter
 * @property-read Unit|null $unit
 * @property CarbonImmutable $created_at
 * @property CarbonImmutable $updated_at
 * @method static EquipmentParameterLogBuilder query()
 */
final class EquipmentParameterLog extends Model
{
    use HasUuids, HasDefaultRouteBinding;
        
    protected $fillable = [
        'equipment_id',
        'equipment_parameter_id',
        'product_id',
        'lot_id',
        'unit_id',
        'component_id',
        'value',
    ];

    /**
     * @return BelongsTo<Equipment, $this>
     */
    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    /**
     * @return BelongsTo<EquipmentParameter, $this>
     */
    public function parameter(): BelongsTo
    {
        return $this->belongsTo(EquipmentParameter::class);
    }

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return BelongsTo<Lot, $this>
     */
    public function stockProductionLot(): BelongsTo
    {
        return $this->belongsTo(StockProductionLot::class, 'lot_id');
    }

    public $incrementing = false;

    protected $keyType = 'string';

    protected $table = 'eamo_equipment_parameter_logs';





    /**
     * @return HasOne<Unit, $this>
     */
    public function unit(): HasOne
    {
        return $this->hasOne(Unit::class, 'id', 'unit_id');
    }

    /**
     * @return HasOne<Unit, $this>
     */
    public function equipmentParameter(): HasOne
    {
        return $this->hasOne(EquipmentParameter::class, 'id', 'equipment_parameter_id');
    }
}
