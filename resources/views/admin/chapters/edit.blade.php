@extends('layouts.manga-layout')

@section('title', 'Editar Capítulo - ' . $series->name . ' - deepseaScans')

@section('content')
    <h1 class="mb-4">Editar Capítulo {{ $chapter->number }} de {{ $series->name }}</h1>

    {{-- Display validation errors if any --}}
    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <h5 class="alert-heading">¡Error de Validación!</h5>
            <p>Se encontraron los siguientes problemas:</p>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form points to the update route with PUT method --}}
    <form action="{{ route('admin.chapters.update', ['series' => $series->id, 'chapter' => $chapter->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-8">
                {{-- Chapter Number --}}
                <div class="mb-3">
                    <label for="number" class="form-label">Número de Capítulo</label>
                    {{-- Pre-fill with old value or current chapter number, add Bootstrap error class --}}
                    <input type="number" class="form-control bg-dark text-white @error('number') is-invalid @enderror" id="number" name="number" value="{{ old('number', $chapter->number) }}" min="1" required>
                    {{-- Bootstrap validation feedback --}}
                    @error('number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Chapter Title (Optional) --}}
                <div class="mb-3">
                    <label for="title" class="form-label">Título (Opcional)</label>
                     {{-- Pre-fill with old value or current chapter title, add Bootstrap error class --}}
                    <input type="text" class="form-control bg-dark text-white @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $chapter->title) }}">
                     {{-- Bootstrap validation feedback --}}
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Field to ADD NEW Pages --}}
                <div class="mb-3">
                    <label for="new_pages" class="form-label">Añadir Nuevas Páginas (Opcional)</label>
                    {{-- Add Bootstrap error classes --}}
                    <input type="file" class="form-control bg-dark text-white @error('new_pages') is-invalid @enderror @error('new_pages.*') is-invalid @enderror" id="new_pages" name="new_pages[]" multiple accept="image/jpeg,image/png,image/gif,image/webp"> {{-- Added webp --}}
                    <div class="form-text text-muted">Selecciona imágenes adicionales para añadir al final del capítulo. Formatos aceptados: JPG, PNG, GIF, WEBP.</div>
                    {{-- Display errors related to new pages upload --}}
                    @error('new_pages') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    @error('new_pages.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                {{-- Display Current Pages Info --}}
                <div class="mb-3">
                    <label class="form-label">Páginas Actuales</label>
                    <div class="p-3 rounded border border-secondary bg-dark-subtle">
                        @php $pageCount = $chapter->pages()->count(); @endphp {{-- Get count efficiently --}}
                        @if($pageCount > 0)
                            <p>Este capítulo tiene actualmente {{ $pageCount }} página(s).</p>
                            <small class="text-muted">La gestión individual de páginas (ver/borrar) no está implementada en este formulario.</small>
                        @else
                            <p class="text-muted">Este capítulo no tiene páginas actualmente.</p>
                        @endif
                    </div>
                </div>

            </div>

            {{-- Sidebar Info --}}
            <div class="col-md-4">
                <div class="card admin-card text-white mb-4"> {{-- Use admin-card style --}}
                    <div class="card-body">
                        <h5 class="card-title">Información del Capítulo</h5>
                        <p><strong>Serie:</strong> {{ $series->name }}</p>
                        <p><strong>Capítulo Editado:</strong> {{ $chapter->number }}</p>
                        <p><strong>Páginas actuales:</strong> {{ $pageCount }}</p> {{-- Use cached count --}}
                        <p><strong>Fecha de creación:</strong> {{ $chapter->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                {{-- Preview (Optional) --}}
                {{-- <div class="card admin-card text-white">
                    <div class="card-body">
                        <h5 class="card-title">Vista previa (1ª pág.)</h5>
                        @php $firstPage = $chapter->pages()->orderBy('page_number', 'asc')->first(); @endphp
                        @if($firstPage)
                            <img src="{{ asset('storage/' . $firstPage->image_path) }}" class="img-fluid" alt="Primera página">
                        @else
                            <p class="text-center text-muted">No hay páginas</p>
                        @endif
                    </div>
                </div> --}}
                 {{-- Commented out preview section for simplicity, uncomment if needed --}}
            </div>
        </div>

        {{-- Form Action Buttons --}}
        <div class="d-flex gap-2 mt-4"> {{-- Added margin-top --}}
            <button type="submit" class="btn btn-primary">Actualizar Capítulo</button>
            {{-- FIX: Changed route from 'admin.series.show' to 'admin.series.edit' --}}
            <a href="{{ route('admin.series.edit', $series->id) }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
@endsection