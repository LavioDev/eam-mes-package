<?php

declare(strict_types=1);

namespace Modules\Equipment\ParameterLog\Presentation\Requests;

use App\Rules\IsValidId;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Manufacturing\Lot\Infrastructure\Models\Lot;
use Modules\Masterdata\Equipment\Infrastructure\Models\Equipment;
use Modules\Masterdata\Equipment\Infrastructure\Models\EquipmentParameter;
use Modules\Masterdata\Product\Infrastructure\Models\Product;

/**
 * @property-read string $name
 */
final class SaveEquipmentParameterLogRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'equipment_id' => [
                'required',
                'string',
                'min:1',
                new IsValidId(),
                'max:36',
            ],
            'product_id' => [
                'nullable',
                'string',
                'min:0',
                new IsValidId(),
                'max:36',
            ],
            'lot_id' => [
                'nullable',
                'string',
                'min:0',
                new IsValidId(),
                'max:36',
            ],
            'parameters' => ['nullable', 'array', 'min:1'],
            'parameters.*.equipment_parameter_id' => [
                'required',
                'string',
                'min:1',
                new IsValidId(),
                'max:36',
            ],
            'parameters.*.value' => ['nullable', 'numeric'],
        ];
    }
}
