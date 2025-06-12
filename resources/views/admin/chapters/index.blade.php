{{-- resources/views/admin/chapters/index.blade.php --}}
@extends('layouts.manga-layout')

@section('title', 'Gestionar Capítulos - ' . $series->name)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gestionar Capítulos de: {{ $series->name }}</h1>
        <div>
            {{-- Link to create a new chapter for this series --}}
            <a href="{{ route('admin.chapters.create', $series->id) }}" class="btn btn-primary">Añadir Nuevo Capítulo</a>
            {{-- Link back to the main series list --}}
            <a href="{{ route('admin.series.index') }}" class="btn btn-secondary ms-2">Volver a Series</a>
        </div>
    </div>

    {{-- Display success messages --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Chapters Table --}}
    <div class="table-responsive">
        <table class="table table-dark table-striped table-hover">
            <thead>
                <tr>
                    <th>Número</th>
                    <th>Título</th>
                    <th>Páginas</th>
                    <th>Fecha Creación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($chapters as $chapter) {{-- Use the paginated $chapters variable --}}
                    <tr>
                        <td>{{ $chapter->number }}</td>
                        <td>{{ $chapter->title ?? '-- Sin Título --' }}</td>
                        <td>{{ $chapter->pages()->count() }}</td> {{-- Consider loading count efficiently --}}
                        <td>{{ $chapter->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                {{-- Public view link --}}
                                <a href="{{ route('chapters.show', [$series->id, $chapter->number]) }}" class="btn btn-sm btn-info" target="_blank" title="Ver Capítulo">Ver</a>
                                {{-- EDIT LINK - Points to the edit route --}}
                                <a href="{{ route('admin.chapters.edit', ['series' => $series->id, 'chapter' => $chapter->id]) }}" class="btn btn-sm btn-warning" title="Editar Capítulo">Editar</a>
                                {{-- DELETE FORM --}}
                                <form action="{{ route('admin.chapters.destroy', ['series' => $series->id, 'chapter' => $chapter->id]) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este capítulo y todas sus páginas?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Eliminar Capítulo">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">No hay capítulos para esta serie.</td>
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