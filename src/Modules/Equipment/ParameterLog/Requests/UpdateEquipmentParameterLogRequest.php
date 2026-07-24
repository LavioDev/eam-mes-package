<?php

declare(strict_types=1);

namespace Modules\Equipment\ParameterLog\Requests;

use App\Rules\IsValidId;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string|null $name
 */
final class UpdateEquipmentParameterLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'equipment_id' => [
                'sometimes',
                'required',
                'string',
                'min:1',
                new IsValidId(),
                'max:36',
            ],
            'equipment_parameter_id' => [
                'sometimes',
                'required',
                'string',
                'min:1',
                new IsValidId(),
                'max:36',
            ],
            'unit_id' => [
                'sometimes',
                'nullable',
                'string',
                'min:0',
                new IsValidId(),
                'max:36',
            ],
            'value' => ['sometimes', 'nullable', 'string', 'min:1', 'max:36'],
            'user_id' => [
                'sometimes',
                'nullable',
                'string',
                'min:0',
                new IsValidId(),
                'max:36',
            ],
            'recorded_at' => ['sometimes', 'nullable', 'date'],
        ];
    }
}
