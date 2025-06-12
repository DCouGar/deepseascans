@extends('layouts.manga-layout')

@section('title', 'Gestionar Capítulos - ' . $series->name . ' - deepseaScans')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Capítulos de {{ $series->name }}</h1>
        <div>
            <a href="{{ route('admin.chapters.create', $series->id) }}" class="btn btn-primary">Añadir Capítulo</a>
            <a href="{{ route('admin.series.index') }}" class="btn btn-secondary ms-2">Volver a Series</a>
        </div>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="row mb-4">
        <div class="col-md-3">
            <img src="{{ $series->cover_image ? asset('storage/' . $series->cover_image) : '/images/default-cover.jpg' }}" 
                 alt="{{ $series->name }}" class="img-fluid rounded">
        </div>
        <div class="col-md-9">
            <div class="card bg-dark text-white">
                <div class="card-body">
                    <h5 class="card-title">Información de la Serie</h5>
                    <div class="row mb-2">
                        <div class="col-md-3 fw-bold">Autor:</div>
                        <div class="col-md-9">{{ $series->author }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 fw-bold">Artista:</div>
                        <div class="col-md-9">{{ $series->artist }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 fw-bold">Género:</div>
                        <div class="col-md-9">{{ $series->genre }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 fw-bold">Estado:</div>
                        <div class="col-md-9">{{ $series->status }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 fw-bold">Total de Capítulos:</div>
                        <div class="col-md-9">{{ $series->chapters->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th>Número</th>
                    <th>Título</th>
                    <th>Páginas</th>
                    <th>Fecha de Creación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($series->chapters()->orderBy('number', 'desc')->get() as $chapter)
                    <tr>
                        <td>{{ $chapter->number }}</td>
                        <td>{{ $chapter->title ?? 'Capítulo ' . $chapter->number }}</td>
                        <td>{{ $chapter->pages->count() }}</td>
                        <td>{{ $chapter->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('chapters.show', [$series->id, $chapter->number]) }}" class="btn btn-sm btn-info" target="_blank">Ver</a>
                                <a href="{{ route('admin.chapters.edit', [$series->id, $chapter->id]) }}" class="btn btn-sm btn-warning">Editar</a>
                                <form action="{{ route('admin.chapters.destroy', [$series->id, $chapter->id]) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este capítulo?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No hay capítulos disponibles.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
