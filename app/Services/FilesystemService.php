<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

class FilesystemService
{
    public function deleteDirectory(string $dir): bool
    {
        if (! file_exists($dir)) {
            return true;
        }

        if (! is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            if (! $this->deleteDirectory("$dir/$item")) {
                return false;
            }
        }

        return rmdir($dir);
    }

    public function getStorageUrl(string $filePath): string
    {
        return Str::of(Storage::url($filePath))
            ->when(app()->isLocal(), function (Stringable $string) {
                return $string->replace('localhost', 'minio');
            });
    }

    public function createTempDirectory(string $resource, string|int $resourceId): string
    {
        $tempDirPath = sys_get_temp_dir().'/'.sprintf("$resource-%s-%d", $resourceId, time());

        if (! mkdir($tempDirPath) && ! is_dir($tempDirPath)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $tempDirPath));
        }

        return $tempDirPath;
    }

    /**
     * @throws \Throwable
     */
    public function extractExtensionJsonFile(string $basePath, string $file): void
    {
        $zip = new \ZipArchive();
        $isZipOpen = $zip->open("$basePath/$file");
        throw_unless($isZipOpen === true, new \RuntimeException("Couldn't open the zip file $basePath/$file"));

        if ($zip->numFiles > 80) {
            $zip->close();

            return;
        }

        $targetFileName = 'extension.json';
        for ($i = 0; $i < $zip->numFiles; $i++) {
            if ($targetFileName === $zip->getNameIndex($i)) {
                $zip->extractTo("$basePath/extracted/", [$targetFileName]);
                break;
            }
        }

        $zip->close();
    }
}
