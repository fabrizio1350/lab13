<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mis Álbumes
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if(session('success'))
                    <div class="mb-4 text-green-600">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('albums.store') }}" class="mb-6 flex gap-2">
                    @csrf
                    <input type="text" name="title" placeholder="Nombre del álbum"
                        class="border rounded px-3 py-2 flex-1" required>
                    <input type="text" name="description" placeholder="Descripción (opcional)"
                        class="border rounded px-3 py-2 flex-1">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                        Crear Álbum
                    </button>
                </form>

                <table class="w-full table-auto border">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-2 border">Título</th>
                            <th class="p-2 border">Fotos</th>
                            <th class="p-2 border">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($albums as $album)
                        <tr>
                            <td class="p-2 border">{{ $album->title }}</td>
                            <td class="p-2 border">{{ $album->photos_count }}</td>
                            <td class="p-2 border">
                                <a href="{{ route('albums.show', $album->id) }}"
                                    class="text-blue-500 mr-2">Ver</a>
                                <form action="{{ route('albums.destroy', $album->id) }}"
                                    method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="p-4 text-center text-gray-500">
                                No tienes álbumes aún.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>