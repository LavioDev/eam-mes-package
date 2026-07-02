<?php

declare(strict_types=1);

namespace Modules\Equipment\ErrorMonitoring\Presentation\Requests;

use App\Rules\IsValidId;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Core\User\Infrastructure\Models\User;

/**
 * @property-read string|null $name
 */
final class UpdateEquipmentErrorLogRequest extends FormRequest
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
            'handler_id' => [
                'nullable',
                'string',
                'min:0',
                'max:36',
                new IsValidId(),
                Rule::exists(User::class, 'id'),
            ],
        ];
    }
} 
