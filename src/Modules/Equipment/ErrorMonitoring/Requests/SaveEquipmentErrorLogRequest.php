<?php

declare(strict_types=1);

namespace Modules\Equipment\ErrorMonitoring\Requests;

use App\Rules\IsValidId;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Equipment\ErrorMonitoring\Models\EquipmentErrorLog;
use Modules\Masterdata\Equipment\Infrastructure\Models\Equipment;
use Modules\Masterdata\Equipment\Infrastructure\Models\EquipmentError;

/**
 * @property-read string $name
 */
final class SaveEquipmentErrorLogRequest extends FormRequest
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
            'equipment_error_id' => [
                'required',
                'string',
                'min:1',
                'max:36',
                new IsValidId(),
                'exists:equipment_errors,id',
            ],
            'equipment_error_log_id' => [
                'required',
                'string',
                'min:1',
                'max:36',
                new IsValidId(),
                'exists:equipment_error_logs,id',
            ],
            'fix' => ['nullable', 'string', 'min:0', 'max:250'],
            'reason' => ['nullable', 'string', 'min:0', 'max:250'],
            'protection_measures' => ['nullable', 'string', 'min:0', 'max:250'],
        ];
    }
}
