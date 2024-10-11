<?php 
namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class StorageService
{
    /**
     * Get files in the storage
     *
     * @param string $directory
     * @return string|null
     */
    public function readStorageFiles(string $directory = 'private'): array
    {
        $directory = storage_path('\app\private');
        $files = File::files($directory);

        $excelFiles = [];
        foreach ($files as $file) {
            if ($file->getExtension() == 'xlsx') {
                $excelFiles[] = $file->getFilename();
            }
        }

        return $excelFiles;
    }
}