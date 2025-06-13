@extends('layouts.manga-layout')

@section('title', 'Editar Serie - ' . $series->name . ' - deepseaScans')

@section('content')
    <h1 class="mb-4">Editar Serie: {{ $series->name }}</h1>
    
    <form action="{{ route('admin.series.update', $series->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-md-8">
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" class="form-control bg-dark text-white" id="name" name="name" value="{{ old('name', $series->name) }}" required>
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="author" class="form-label">Autor</label>
                    <input type="text" class="form-control bg-dark text-white" id="author" name="author" value="{{ old('author', $series->author) }}" required>
                    @error('author')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="artist" class="form-label">Artista</label>
                    <input type="text" class="form-control bg-dark text-white" id="artist" name="artist" value="{{ old('artist', $series->artist) }}" required>
                    @error('artist')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="genre" class="form-label">Género</label>
                    <input type="text" class="form-control bg-dark text-white" id="genre" name="genre" value="{{ old('genre', $series->genre) }}" required>
                    @error('genre')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="status" class="form-label">Estado</label>
                    <select class="form-select bg-dark text-white" id="status" name="status" required>
                        <option value="Ongoing" {{ old('status', $series->status) == 'Ongoing' ? 'selected' : '' }}>En emisión</option>
                        <option value="Completed" {{ old('status', $series->status) == 'Completed' ? 'selected' : '' }}>Completado</option>
                    </select>
                    @error('status')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="synopsis" class="form-label">Sinopsis</label>
                    <textarea class="form-control bg-dark text-white" id="synopsis" name="synopsis" rows="5" required>{{ old('synopsis', $series->synopsis) }}</textarea>
                    @error('synopsis')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="cover_image" class="form-label">Imagen de Portada</label>
                    <input type="file" class="form-control bg-dark text-white" id="cover_image" name="cover_image">
                    <div class="form-text">Deja en blanco para mantener la imagen actual</div>
                    @error('cover_image')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <div class="card bg-dark">
                        <div class="card-body">
                            <h5 class="card-title">Imagen actual</h5>
                            <img id="cover_preview" src="{{ $series->cover_image ? '/storage/' . $series->cover_image : '/images/default-cover.jpg' }}" class="img-fluid" alt="Vista previa">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Actualizar Serie</button>
            <a href="{{ route('admin.series.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
@endsection

@section('scripts')
<script>
    document.getElementById('cover_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('cover_preview').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
