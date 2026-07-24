<?php

declare(strict_types=1);

namespace Modules\Equipment\ParameterLog\Requests;

use App\Rules\IsValidId;
use Illuminate\Foundation\Http\FormRequest;

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
            'user_id' => [
                'nullable',
                'string',
                'min:0',
                new IsValidId(),
                'max:36',
            ],
            'recorded_at' => ['nullable', 'date'],
            'parameters' => ['nullable', 'array', 'min:1'],
            'parameters.*.equipment_parameter_id' => [
                'required',
                'string',
                'min:1',
                new IsValidId(),
                'max:36',
            ],
            'parameters.*.value' => ['nullable', 'numeric'],
            'parameters.*.unit_id' => [
                'nullable',
                'string',
                'min:0',
                new IsValidId(),
                'max:36',
            ],
        ];
    }
}
