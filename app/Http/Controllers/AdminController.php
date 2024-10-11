<?php

namespace App\Http\Controllers;

use App\Services\StorageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class AdminController
{
    public function showExportsList(StorageService $storage_service): JsonResponse
    {
        $excelFiles = $storage_service->readStorageFiles();
        return response()->json($excelFiles);
    }

    public function downloadExcelFiles(Request $request): HttpFoundationResponse
    {
        $file_name = $request->input('file_name');
        $file_path = storage_path('\app\private\\'. $file_name);
        if(file_exists($file_path))
        {
            return response()->download($file_path);
        }
        else
        {
            return response()->json(['File not found'], 404);
        }
    }
}
