<?php

namespace App\Http\Controllers;

use App\Settings\AboutSettings;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function __invoke(AboutSettings $aboutSettings): array
    {
        return [
            'content' => $aboutSettings->content
        ];
    }
}
