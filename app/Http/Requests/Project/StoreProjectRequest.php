<?php

namespace App\Http\Requests\Project;

use App\Enums\ProjectTypeEnum;
use App\Models\Category;
use App\Models\Domain;
use App\Models\User;
use App\Services\ProjectService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreProjectRequest extends FormRequest
{
    public static string $packageNameRegex = '/^[a-z][a-z0-9_]*(\.[a-z0-9][a-z0-9_]*)*$/';

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
        return [
            'category_id' => ['bail', 'required', 'numeric', Rule::exists(Category::class, 'id')],
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'type' => ['required', Rule::enum(ProjectTypeEnum::class)],
            'short_description' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['required', 'string', 'min:3', 'max:1024'],
            'links' => ['required', 'array:source_code'],
            'links.source_code' => ['required', 'string', 'url', 'max:255', 'starts_with:https://github.com'],
            /* @var int[] */
            'maintainers' => ['bail', 'nullable', 'array', 'between:0,5'],
            'maintainers.*' => ['bail', 'required', 'numeric', Rule::notIn([Auth::id()]), Rule::exists(User::class, 'id')],
            'verified_domain_id' => [
                'bail',
                'required',
                'integer',
                Rule::exists('domains', 'id')
                    ->where('user_id', Auth::id())
                    ->whereNotNull('verified_at'),
            ],
            'package_name' => [
                'bail',
                'required',
                'string',
                'min:3',
                'max:255',
                'not_regex:/^defaults\./',
                'regex:'.static::$packageNameRegex,
                Rule::unique('projects'),
                function ($attribute, $value, $fail) {
                    $domain = Domain::find($this->input('verified_domain_id'));
                    $expectedDomain = app(ProjectService::class)->convertToPackageName('', $domain->name);

                    if (! Str::startsWith($value, $expectedDomain)) {
                        $fail("You don't own or verified the {$attribute} domain.");
                    }
                },
            ],
        ];
    }
}
