<?php

use App\Http\Controllers\Api\AlbumApiController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
// Grupo protegido con Sanctum (autenticación por token)
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('albums', AlbumApiController::class)
         ->only(['index', 'store', 'show', 'destroy']);
});

// Ruta pública de prueba
Route::get('/ping', fn() => response()->json(['status' => 'ok']));
// routes/api.php — ruta pública para obtener token
Route::post('/token', function (Request $request) {
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    $user = \App\Models\User::where('email', $request->email)->first();

    if (!$user || !\Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Credenciales inválidas'], 401);
    }

    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json(['token' => $token]);
});
