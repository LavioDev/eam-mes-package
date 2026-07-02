<?php

declare(strict_types=1);

namespace Modules\Equipment\Checklist\Presentation\Requests;

use App\Rules\IsValidId;
use Illuminate\Foundation\Http\FormRequest;

class UpdateChecklistDetailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'session_id' => [
                'required',
                'string',
                'min:1',
                'max:36',
                new IsValidId(),
                'exists:checklist_sessions,id',
            ],
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
