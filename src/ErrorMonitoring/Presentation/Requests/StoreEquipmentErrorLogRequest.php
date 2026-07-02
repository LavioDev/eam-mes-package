<?php

declare(strict_types=1);

namespace Modules\Equipment\ErrorMonitoring\Presentation\Requests;

use App\Rules\IsValidId;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Equipment\ErrorMonitoring\Infrastructure\Models\EquipmentErrorLog;
use Modules\Masterdata\Equipment\Infrastructure\Models\Equipment;
use Modules\Masterdata\Equipment\Infrastructure\Models\EquipmentError;

/**
 * @property-read string $name
 */
final class StoreEquipmentErrorLogRequest extends FormRequest
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
                'max:36',
                new IsValidId(),
                Rule::exists(Equipment::class, 'id'),
            ],
            'equipment_error_id' => [
                'required',
                'string',
                'min:1',
                'max:36',
                new IsValidId(),
                Rule::exists(EquipmentError::class, 'id'),
            ],
        ];
    }
} 
