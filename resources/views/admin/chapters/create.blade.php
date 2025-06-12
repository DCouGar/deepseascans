@extends('layouts.manga-layout')

@section('title', 'Añadir Capítulo - ' . $series->name . ' - deepseaScans')

@section('content')
    <h1 class="mb-4">Añadir Capítulo para {{ $series->name }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form points to the correct store route --}}
    <form action="{{ route('admin.chapters.store', $series->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-md-8">
                {{-- Chapter Number --}}
                <div class="mb-3">
                    <label for="number" class="form-label">Número de Capítulo</label>
                    <input type="number" class="form-control bg-dark text-white @error('number') is-invalid @enderror" id="number" name="number" value="{{ old('number', $series->chapters->max('number') + 1) }}" min="1" required> {{-- Suggest next number --}}
                    @error('number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Chapter Title (Optional) --}}
                <div class="mb-3">
                    <label for="title" class="form-label">Título (Opcional)</label>
                    <input type="text" class="form-control bg-dark text-white @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}">
                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Chapter Pages (Multiple File Upload) --}}
                <div class="mb-3">
                    <label for="pages" class="form-label">Páginas del Capítulo</label>
                    <input type="file" class="form-control bg-dark text-white @error('pages') is-invalid @enderror @error('pages.*') is-invalid @enderror" id="pages" name="pages[]" multiple required accept="image/jpeg,image/png,image/gif,image/webp"> {{-- Added webp --}}
                    <div class="form-text text-muted">Selecciona todas las imágenes del capítulo en orden. Formatos aceptados: JPG, PNG, GIF, WEBP.</div>
                    @error('pages') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror {{-- Error for the array itself --}}
                    @error('pages.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror {{-- Error for individual files in the array --}}
                </div>

                <div class="alert alert-info">
                    <strong>Nota:</strong> Las imágenes se procesarán según el orden de selección (o alfabético si el navegador no lo garantiza).
                </div>
            </div>

            {{-- Series Info Sidebar --}}
            <div class="col-md-4">
                 {{-- Use admin-card style --}}
                <div class="card admin-card text-white h-100">
                    <div class="card-body">
                        <h5 class="card-title">Información de la Serie</h5>
                        <p><strong>Nombre:</strong> {{ $series->name }}</p>
                        <p><strong>Autor:</strong> {{ $series->author }}</p>
                         {{-- Use count if available --}}
                        <p><strong>Capítulos actuales:</strong> {{ $series->chapters_count ?? $series->chapters->count() }}</p>
                        <p><strong>Estado:</strong> {{ $series->status }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Action Buttons --}}
        <div class="d-flex gap-2 mt-4"> {{-- Added margin top --}}
            <button type="submit" class="btn btn-primary">Guardar Capítulo</button>
             {{-- FIX: Changed route from 'admin.series.show' to 'admin.series.index' --}}
            <a href="{{ route('admin.series.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
@endsection