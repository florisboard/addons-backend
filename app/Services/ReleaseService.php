<?php

namespace App\Services;

class ReleaseService
{
    /**
     * @return array<string,mixed>
     *
     * @throws \JsonException
     * @throws \Throwable
     */
    public function parseExtensionJson(string $tempDirPath): array|false
    {
        $finalPath = "$tempDirPath/extracted/extension.json";

        if (! file_exists($finalPath)) {
            return false;
        }

        return json_decode(file_get_contents($finalPath), true, 124, JSON_THROW_ON_ERROR);
    }

    /**
     * @param  array<string,mixed>  $result
     *
     * @throws \JsonException
     */
    public function replaceExtensionJson(string $tempDirPath, array $result): bool
    {
        return (bool) file_put_contents("$tempDirPath/extracted/extension.json", json_encode($result, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT));
    }
}
