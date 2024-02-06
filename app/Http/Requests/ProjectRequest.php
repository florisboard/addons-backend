<?php

namespace App\Http\Requests;

use App\Enums\ProjectTypeEnum;
use App\Models\Category;
use App\Models\Project;
use App\Models\User;
use App\Rules\FileUpload;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
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

        /** @var Project|null $project */
        $project = $this->route('project');

        return [
            'category_id' => ['bail', 'required', 'numeric', Rule::exists(Category::class, 'id')],
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'package_name' => ['bail', 'required', 'string', 'min:3', 'max:255', 'regex:/^([A-Za-z]{1}[A-Za-z\d_]*\.)+[A-Za-z][A-Za-z\d_]*$/', Rule::unique('projects')->ignore($project?->id)],
            'type' => ['required', Rule::enum(ProjectTypeEnum::class)],
            'short_description' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['required', 'string', 'min:3', 'max:1024'],
            'home_page' => ['nullable', 'string', 'url', 'max:255'],
            'support_email' => ['nullable', 'string', 'min:3', 'max:255', 'email'],
            'support_site' => ['nullable', 'string', 'max:255', 'url'],
            'donate_site' => ['nullable', 'string', 'max:255', 'url'],
            /* @var int[] */
            'maintainers' => ['bail', 'nullable', 'array', 'between:0,5'],
            'maintainers.*' => ['bail', 'required', 'numeric', Rule::notIn([Auth::id()]), Rule::exists(User::class, 'id')],
            'screenshots_path' => ['nullable', 'array', 'between:0,5'],
            'screenshots_path.*' => ['required', 'string', new FileUpload(['image/png', 'image/jpeg'])],
        ];
    }
}
