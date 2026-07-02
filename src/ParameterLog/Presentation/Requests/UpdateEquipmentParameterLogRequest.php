<?php

declare(strict_types=1);

namespace Modules\Equipment\ParameterLog\Presentation\Requests;

use App\Rules\IsValidId;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Equipment\ParameterLog\Infrastructure\Models\EquipmentParameterLog;

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
                'required',
                'string',
                'min:1',
                new IsValidId(),
                'max:36',
            ],
            'equipment_parameter_id' => [
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
            'value' => ['required', 'string', 'min:1', 'max:36'],
        ];
    }
}
