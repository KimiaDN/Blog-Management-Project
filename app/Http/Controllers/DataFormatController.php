<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class DataFormatController
{
    public function reformat(): JsonResponse
    {
        $response = Http::get('https://api.sokanacademy.com/api/announcements/blog-index-header');
        $response_json = json_decode($response->body(), true);
        $collection_data = collect($response_json['data']);
        $flattened = $collection_data->map(function ($items) {
            return $items['all'];
        });
        
        $grouped = $flattened->groupBy('category_name')->map(function(object $items)
        {
            return $items->mapWithKeys(function(array $item)
            {
                return [$item['title'] => $item['views_count']];
            });
        });
        
        return response()->json($grouped->all());
    }

}
