<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Album;
use Illuminate\Support\Facades\Auth;

class AlbumApiController extends Controller
{
    /** GET /api/albums
     *  Devuelve álbumes del usuario autenticado con conteo de fotos.
     */
    public function index()
    {
        $albums = Auth::user()
                      ->albums()
                      ->withCount('photos')
                      ->get();

        return response()->json([
            'data'  => $albums,
            'total' => $albums->count(),
        ]);
    }

    /** GET /api/albums/{album} */
    public function show(Album $album)
    {
        abort_if($album->user_id !== Auth::id(), 403,
            'No autorizado.');

        $album->load('photos');

        return response()->json(['data' => $album]);
    }

    /** POST /api/albums */
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:150',
            'description' => 'nullable|string',
        ]);

        $album = Auth::user()->albums()->create(
            $request->only('title', 'description')
        );

        return response()->json(['data' => $album], 201);
    }

    /** DELETE /api/albums/{album} */
    public function destroy(Album $album)
    {
        abort_if($album->user_id !== Auth::id(), 403);
        $album->delete();
        return response()->json(['message' => 'Álbum eliminado.']);
    }
}
