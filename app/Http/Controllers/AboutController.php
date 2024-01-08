<?php

namespace App\Http\Controllers;

use App\Settings\AboutSettings;

class AboutController extends Controller
{
    /**
     * @return array{content: string}
     */
    public function __invoke(AboutSettings $aboutSettings): array
    {
        return [
            'content' => $aboutSettings->content,
        ];
    }
}
