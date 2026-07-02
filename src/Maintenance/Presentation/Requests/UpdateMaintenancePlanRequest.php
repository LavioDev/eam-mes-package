<?php

declare(strict_types=1);

namespace Modules\Equipment\Maintenance\Presentation\Requests;

use App\Rules\IsValidId;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Core\User\Infrastructure\Models\User;
use Modules\Equipment\Maintenance\Infrastructure\Models\MaintenancePlan;

/**
 * @property-read string|null $name
 */
final class UpdateMaintenancePlanRequest extends FormRequest
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
            'id' => [
                'required',
                'string',
                'min:1',
                'max:36',
                new IsValidId(),
                Rule::exists(MaintenancePlan::class, 'id'),
            ],
            'date' => ['required', 'string', 'min:1', 'max:255'],
            'start_time' => ['required', 'string', 'min:1', 'max:255'],
            'end_time' => ['required', 'string', 'min:1', 'max:255'],
            'notes' => ['nullable', 'string', 'min:0', 'max:1000'],
        ];

    }
}
