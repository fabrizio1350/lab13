<?php

namespace App\Http\Controllers;
use App\Models\Album;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;

class AlbumController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
        ];
    }

    /** GET /albums */
    public function index()
    {
        // Eager loading: carga álbumes + conteo de fotos en una sola query
        $albums = Auth::user()
            ->albums()
            ->withCount('photos')
            ->latest()
            ->get();

        return view('albums.index', compact('albums'));
    }

    /** GET /albums/{album} */
    public function show(Album $album)
    {
        // Verificar que el álbum pertenece al usuario autenticado
        abort_if($album->user_id !== Auth::id(), 403);

        // Cargar las fotos del álbum (eager loading)
        $album->load('photos');

        return view('albums.show', compact('album'));
    }

    /** POST /albums */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
        ]);

        Auth::user()->albums()->create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('albums.index')
            ->with('success', 'Álbum creado.');
    }

    /** DELETE /albums/{album} */
    public function destroy(Album $album)
    {
        abort_if($album->user_id !== Auth::id(), 403);
        $album->delete(); // cascade elimina las fotos (definido en la migración)
        return back()->with('success', 'Álbum eliminado.');
    }
}
