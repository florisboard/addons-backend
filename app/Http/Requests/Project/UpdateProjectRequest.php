<?php

namespace App\Http\Requests\Project;

use App\Enums\ProjectTypeEnum;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $base = [
            'category_id' => ['bail', 'required', 'numeric', Rule::exists(Category::class, 'id')],
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'type' => ['required', Rule::enum(ProjectTypeEnum::class)],
            'short_description' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['required', 'string', 'min:3', 'max:1024'],
            'links' => ['required', 'array:source_code'],
            'links.source_code' => ['required', 'string', 'url', 'max:255'],
        ];

        /** @var Project $project */
        $project = $this->route('project');

        if ($project?->user_id === Auth::Id()) {
            /** @var int[] */
            $base['maintainers'] = ['bail', 'nullable', 'array', 'between:0,5'];
            $base['maintainers.*'] = ['bail', 'required', 'numeric', Rule::notIn([Auth::id()]), Rule::exists(User::class, 'id')];
        }

        return $base;
    }
}
