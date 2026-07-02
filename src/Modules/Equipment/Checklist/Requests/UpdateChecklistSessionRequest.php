<?php

declare(strict_types=1);

namespace Modules\Equipment\Checklist\Requests;

use Illuminate\Foundation\Http\FormRequest;
// use Modules\Equipment\Checklist\Models\ChecklistSession;

/**
 * @property-read string|null $name
 */
final class UpdateChecklistSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'min:1', 'max:255'],
            // Add more validation rules as needed
        ];
    }
}
