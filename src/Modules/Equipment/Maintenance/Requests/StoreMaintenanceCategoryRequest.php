<?php

declare(strict_types=1);

namespace Modules\Equipment\Maintenance\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Equipment\Maintenance\Models\MaintenanceItem;
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
final class StoreMaintenanceCategoryRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'min:1',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
                'min:0',
                'max:255',
            ],
        ];
    }
}
