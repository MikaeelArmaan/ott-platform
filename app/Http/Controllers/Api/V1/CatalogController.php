<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Content;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function movies()
    {
        return response()->json([
            'movies' => Content::where('type', 'movie')->where('is_published', true)->latest()->paginate(24)
        ]);
    }

    public function series()
    {
        return response()->json([
            'series' => Content::where('type', 'series')->where('is_published', true)->latest()->paginate(24)
        ]);
    }

    public function show($id)
    {
        return response()->json(['content' => Content::findOrFail($id)]);
    }

    public function search(Request $r)
    {
        $q = trim((string)$r->query('q',''));
        if ($q === '') return response()->json(['results' => []]);

        $results = Content::where('is_published', true)
            ->where('title', 'like', "%{$q}%")
            ->limit(40)->get();

        return response()->json(['results' => $results]);
    }
}
