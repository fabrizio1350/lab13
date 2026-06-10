<?php

namespace App\Console\Commands;

use App\Models\Album;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ImportarAlbumsExternos extends Command
{
    protected $signature   = 'albums:importar {--user=1}';
    protected $description = 'Importa álbumes desde JSONPlaceholder API';

    public function handle()
    {
        $userId = $this->option('user');
        $user   = User::findOrFail($userId);

        $this->info("Importando álbumes para {$user->name}...");

        // GET a API externa
        $response = Http::get('https://jsonplaceholder.typicode.com/albums', [
            'userId' => 1, // primer usuario de la API de prueba
            '_limit' => 5,
        ]);

        if ($response->failed()) {
            $this->error('No se pudo conectar a la API externa.');
            return Command::FAILURE;
        }

        foreach ($response->json() as $item) {
            Album::firstOrCreate(
                ['title' => $item['title'], 'user_id' => $user->id],
                ['description' => 'Importado desde JSONPlaceholder']
            );
        }

        $this->info('Importación completada.');
        return Command::SUCCESS;
    }
}
