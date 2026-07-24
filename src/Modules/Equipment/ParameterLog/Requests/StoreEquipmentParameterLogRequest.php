<?php

declare(strict_types=1);

namespace Modules\Equipment\ParameterLog\Requests;

use App\Rules\IsValidId;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $name
 */
final class StoreEquipmentParameterLogRequest extends FormRequest
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
                'nullable',
                'string',
                'min:1',
                new IsValidId(),
                'max:36',
            ],
            'value' => ['nullable', 'string', 'min:1', 'max:36'],
            'user_id' => [
                'nullable',
                'string',
                'min:1',
                new IsValidId(),
                'max:36',
            ],
            'recorded_at' => ['nullable', 'date'],
        ];
    }
}
