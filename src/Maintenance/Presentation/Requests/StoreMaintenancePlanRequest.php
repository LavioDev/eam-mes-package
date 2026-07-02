<?php

declare(strict_types=1);

namespace Modules\Equipment\Maintenance\Presentation\Requests;

use App\Rules\IsValidId;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Equipment\Maintenance\Infrastructure\Models\MaintenanceItem;
use Modules\Masterdata\Equipment\Infrastructure\Models\Equipment;

/**
 * @property-read string $plan_code
 * @property-read string $equipment_id
 * @property-read string $maintenance_type
 * @property-read int $maintenance_item_id
 * @property-read string $cycle_type
 * @property-read string $using_unit
 * @property-read string $maintenance_unit
 * @property-read string $maintenance_date
 * @property-read string $start_time
 * @property-read string $end_time
 * @property-read string|null $notes
 */
final class StoreMaintenancePlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int|string>>
     */
    public function rules(): array
    {
        return [
            'plan_code' => [
                'nullable',
                'string',
                'min:0',
                'max:255',
            ],
            'equipment_id' => [
                'required',
                'string',
                'min:1',
                'max:36',
                new IsValidId(),
                Rule::exists(Equipment::class, 'id'),
            ],
            'maintenance_type' => [
                'required',
                'string',
                'min:1',
                'max:255',
            ],
            'cycle_type' => [
                'nullable',
                'string',
                'min:0',
                'max:255',
            ],
            // Uncomment if cycle_interval is still expected
            'cycle_interval' => [
                'nullable',
                'integer',
                'min:1',
                'max:999999',
            ],
            'maintenance_item_id' => [
                'required',
                'string',
                'min:1',
                'max:36',
                new IsValidId(),
                Rule::exists(MaintenanceItem::class, 'id'),
            ],
            'using_unit' => [
                'required',
                'string',
                'min:1',
                'max:255',
            ],
            'maintenance_unit' => [
                'required',
                'string',
                'min:1',
                'max:255',
            ],
            'maintenance_date' => [
                'required',
                'date_format:Y-m-d',
            ],
            'start_time' => [
                'required',
                'date_format:Y-m-d H:i:s',
                'before_or_equal:end_time',
            ],
            'end_time' => [
                'required',
                'date_format:Y-m-d H:i:s',
                'after_or_equal:start_time',
            ],
            'notes' => [
                'nullable',
                'string',
                'min:0',
                'max:255',
            ],
        ];
    }
}
