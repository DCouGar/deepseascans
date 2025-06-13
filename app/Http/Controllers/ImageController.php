<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ImageController extends Controller
{
    public function serveCover($filename)
    {
        Log::info("Intentando servir cover: {$filename}");
        
        $path = public_path('covers/' . $filename);
        
        Log::info("Ruta completa: {$path}");
        Log::info("Archivo existe: " . (file_exists($path) ? 'SI' : 'NO'));
        
        if (!file_exists($path)) {
            Log::error("Archivo no encontrado: {$path}");
            abort(404, 'Cover image not found');
        }
        
        $mimeType = File::mimeType($path);
        
        return response()->file($path, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=3600'
        ]);
    }
    
    public function servePage($seriesId, $chapterId, $filename)
    {
        Log::info("Intentando servir página: series/{$seriesId}/chapters/{$chapterId}/{$filename}");
        
        $path = public_path("series/{$seriesId}/chapters/{$chapterId}/{$filename}");
        
        Log::info("Ruta completa: {$path}");
        Log::info("Archivo existe: " . (file_exists($path) ? 'SI' : 'NO'));
        
        if (!file_exists($path)) {
            Log::error("Archivo no encontrado: {$path}");
            abort(404, 'Page image not found');
        }
        
        $mimeType = File::mimeType($path);
        
        return response()->file($path, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=3600'
        ]);
    }
} 