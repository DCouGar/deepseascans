{{-- resources/views/admin/series/index.blade.php --}}
@extends('layouts.manga-layout')

{{-- CORRECTED: Use a static title, not based on the $series collection --}}
@section('title', 'Administrar Series - deepseaScans')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Administrar Series</h1>
        {{-- Link to the create series page --}}
        <a href="{{ route('admin.series.create') }}" class="btn btn-primary">Añadir Nueva Serie</a>
    </div>

    {{-- Display success messages from session --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-dark table-striped table-hover align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th style="width: 80px;">Portada</th> {{-- Give cover a defined width --}}
                    <th>Nombre</th>
                    <th>Autor</th>
                    <th>Capítulos</th>
                    <th>Estado</th>
                    <th style="min-width: 190px;">Acciones</th> {{-- Wider min-width for 3 buttons --}}
                </tr>
            </thead>
            <tbody>
                {{-- Loop through the $series collection passed from SeriesController@adminIndex --}}
                @forelse($series as $serie) {{-- $series is the Collection, $serie is one Model instance --}}
                    <tr>
                        <td>{{ $serie->id }}</td>
                        <td>
                            {{-- Display cover image thumbnail --}}
                            <img src="{{ $serie->cover_image ? '/covers/' . $serie->cover_image : '/images/default-cover.jpg' }}"
                                 alt="Portada de {{ $serie->name }}"
                                 class="img-fluid" {{-- Make image responsive within its cell --}}
                                 style="max-height: 75px; object-fit: cover;"> {{-- Limit height --}}
                        </td>
                        {{-- Display series properties (using the singular $serie object) --}}
                        <td>{{ $serie->name }}</td>
                        <td>{{ $serie->author }}</td>
                        {{-- Display chapter count (use eager loaded count if available) --}}
                        <td>{{ $serie->chapters_count ?? $serie->chapters->count() }}</td>
                        <td>{{ $serie->status }}</td>
                        {{-- Action buttons for each series --}}
                        <td style="white-space: nowrap;">
                            <div class="d-flex gap-1">
                                {{-- Link to EDIT the series --}}
                                <a href="{{ route('admin.series.edit', $serie->id) }}" class="btn btn-sm btn-warning" title="Editar Serie">Editar</a>
                                {{-- Link to MANAGE CHAPTERS for this series --}}
                                <a href="{{ route('admin.chapters.index', $serie->id) }}" class="btn btn-sm btn-success" title="Gestionar Capítulos">Capítulos</a>
                                {{-- Form to DELETE the series --}}
                                <form action="{{ route('admin.series.destroy', $serie->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta serie y TODOS sus capítulos asociados?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Eliminar Serie">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    {{-- Message shown if the $series collection is empty --}}
                    <tr>
                        <td colspan="7" class="text-center text-muted">No hay series disponibles.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Display pagination links if using paginate() in the controller --}}
    <div class="mt-4">
        {{-- Check if $series is paginated before calling links() --}}
        @if ($series instanceof \Illuminate\Pagination\LengthAwarePaginator)
            {{ $series->links() }}
        @endif
    </div>

@endsection
