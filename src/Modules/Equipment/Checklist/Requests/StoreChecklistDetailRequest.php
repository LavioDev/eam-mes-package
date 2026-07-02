<?php

declare(strict_types=1);

namespace Modules\Equipment\Checklist\Requests;

use App\Rules\IsValidId;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Equipment\Checklist\Models\ChecklistSession;

/**
 * @property-read string $name
 */
final class StoreChecklistDetailRequest extends FormRequest
{
    public function authorize(): bool
    {
        // return $this->user()->can('create', ChecklistSession::class);
        return true;
    }

    public function rules(): array
    {
        return [
            'session_date' => ['nullable', 'string', 'min:0', 'max:255'],
            'checklists' => ['required', 'array'],
            'checklists.*.checklist_id' => [
                'required',
                'string',
                'min:1',
                'max:36',
                new IsValidId(),
                'exists:checklists,id',
            ],
            'checklists.*.result' => ['required', 'in:pass,fail'],
            'checklists.*.description' => ['nullable'],

        ];
    }
}
