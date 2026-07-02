<?php

declare(strict_types=1);

namespace Modules\Equipment\Checklist\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Equipment\Checklist\Infrastructure\Models\ChecklistSession;

/**
 * @property-read string $name
 */
final class StoreChecklistSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', ChecklistSession::class);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:1', 'max:255'],
            // Add more validation rules as needed
        ];
    }
}