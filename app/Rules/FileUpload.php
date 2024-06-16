<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUpload implements ValidationRule
{
    /**
     * @param  string[]  $validMimeTypes
     * @param  string[]  $validExtensions
     */
    public function __construct(
        private readonly array $validMimeTypes = [],
        private readonly array $validExtensions = [],
    ) {
        //
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! Str::of($value)->startsWith('tmp/')) {
            $fail('The path is not correct.');
        }

        if (! empty($this->validExtensions) && ! in_array(explode('.', $value)[1], $this->validExtensions, true)) {
            $fail('The file has an invalid extension.');
        }

        if (! Storage::exists($value)) {
            $fail('The file does not exist.');
        }

        if (! empty($this->validMimeTypes) && ! in_array(Storage::mimeType($value), $this->validMimeTypes, true)) {
            $fail('The file is not a valid mime type.');
        }
    }
}
