{{-- resources/views/admin/chapters/index_all.blade.php --}}
@extends('layouts.manga-layout')

@section('title', 'Todos los Capítulos - deepseaScans')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Todos los Capítulos</h1>
        {{-- Optional: Add a button to go back or to create something global if needed --}}
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Volver al Panel</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-dark table-striped table-hover align-middle">
            <thead>
                <tr>
                    <th>Serie</th> {{-- New column for Series Name --}}
                    <th>Capítulo #</th>
                    <th>Título</th>
                    <th>Páginas</th>
                    <th>Fecha Creación</th>
                    <th style="min-width: 160px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($chapters as $chapter) {{-- Loop through the paginated chapters --}}
                    <tr>
                        <td>
                            {{-- Link to the chapter list page for THIS specific series --}}
                            {{-- Access the series name via the eager loaded relationship --}}
                            @if($chapter->series)
                                {{-- *** CHANGE MADE HERE *** --}}
                                <a href="{{ route('admin.chapters.index', $chapter->series->id) }}">{{ $chapter->series->name }}</a>
                            @else
                                <span class="text-muted">Serie no encontrada</span>
                            @endif
                        </td>
                        <td>{{ $chapter->number }}</td>
                        <td>{{ $chapter->title ?? '-- Sin Título --' }}</td>
                         {{-- Use count() or eager loaded count if available --}}
                        <td>{{ $chapter->pages_count ?? $chapter->pages()->count() }}</td>
                        <td>{{ $chapter->created_at->format('d/m/Y H:i') }}</td>
                        <td style="white-space: nowrap;">
                            <div class="d-flex gap-1">
                                {{-- Use the series from the relationship for route parameters --}}
                                @if($chapter->series)
                                    <a href="{{ route('chapters.show', [$chapter->series->id, $chapter->number]) }}" class="btn btn-sm btn-info" target="_blank" title="Ver Capítulo">Ver</a>
                                    <a href="{{ route('admin.chapters.edit', ['series' => $chapter->series->id, 'chapter' => $chapter->id]) }}" class="btn btn-sm btn-warning" title="Editar Capítulo">Editar</a>
                                    <form action="{{ route('admin.chapters.destroy', ['series' => $chapter->series->id, 'chapter' => $chapter->id]) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este capítulo y todas sus páginas?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Eliminar Capítulo">Eliminar</button>
                                    </form>
                                @else
                                    <span class="text-muted">N/A</span> {{-- No actions if series missing --}}
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                         {{-- Adjust colspan to match the new number of columns --}}
                        <td colspan="6" class="text-center text-muted">No hay capítulos disponibles.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination Links --}}
    <div class="mt-4">
        {{ $chapters->links() }}
    </div>

@endsection