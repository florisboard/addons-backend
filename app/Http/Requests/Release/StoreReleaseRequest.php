<?php

namespace App\Http\Requests\Release;

use App\Rules\FileUpload;
use App\Validations\ValidateReleaseFile;
use App\Validations\ValidateReleaseVersionName;
use Illuminate\Foundation\Http\FormRequest;

class StoreReleaseRequest extends FormRequest
{
    public static string $versionNameRegex = '/^\d+(?:\.\d+){2}$/';

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
            'description' => ['required', 'string', 'min:3', 'max:1024'],
            'version_name' => ['required', 'string', 'regex:'.static::$versionNameRegex],
            'file_path' => ['bail', 'required', new FileUpload(validExtensions: ['flex'])],
        ];
    }

    /**
     * @return mixed[]
     */
    public function after(): array
    {
        $base = [
            new ValidateReleaseVersionName,
        ];

        //        if (! app()->runningUnitTests()) {
        //            $base[] = new ValidateReleaseFile;
        //        }

        return $base;
    }
}
