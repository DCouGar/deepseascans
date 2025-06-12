@extends('layouts.manga-layout')

@section('title', 'Añadir Nueva Serie - deepseaScans')

@section('content')
    <h1 class="mb-4">Añadir Nueva Serie</h1>

    {{-- *** AÑADIDO: Bloque para mostrar TODOS los errores de validación *** --}}
    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <h5 class="alert-heading">¡Error de Validación!</h5>
            <p>Se encontraron los siguientes problemas:</p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    {{-- *** FIN DEL BLOQUE AÑADIDO *** --}}

    <form action="{{ route('admin.series.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-md-8">
                {{-- Campo Nombre --}}
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    {{-- Añadido old() para repoblar --}}
                    <input type="text" class="form-control bg-dark text-white @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        {{-- Cambiado a invalid-feedback para estilo Bootstrap --}}
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Campo Autor --}}
                <div class="mb-3">
                    <label for="author" class="form-label">Autor</label>
                    {{-- Añadido old() --}}
                    <input type="text" class="form-control bg-dark text-white @error('author') is-invalid @enderror" id="author" name="author" value="{{ old('author') }}" required>
                    @error('author')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Campo Artista --}}
                <div class="mb-3">
                    <label for="artist" class="form-label">Artista</label>
                    {{-- Añadido old() --}}
                    <input type="text" class="form-control bg-dark text-white @error('artist') is-invalid @enderror" id="artist" name="artist" value="{{ old('artist') }}" required>
                    @error('artist')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Campo Género --}}
                <div class="mb-3">
                    <label for="genre" class="form-label">Género</label>
                    {{-- Añadido old() --}}
                    <input type="text" class="form-control bg-dark text-white @error('genre') is-invalid @enderror" id="genre" name="genre" value="{{ old('genre') }}" required>
                    @error('genre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Campo Estado --}}
                <div class="mb-3">
                    <label for="status" class="form-label">Estado</label>
                    <select class="form-select bg-dark text-white @error('status') is-invalid @enderror" id="status" name="status" required>
                        <option value="" disabled {{ old('status') ? '' : 'selected' }}>Selecciona...</option> {{-- Mejor añadir opción por defecto --}}
                        <option value="Ongoing" {{ old('status') == 'Ongoing' ? 'selected' : '' }}>En emisión</option>
                        <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>Completado</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Campo Sinopsis --}}
                <div class="mb-3">
                    <label for="synopsis" class="form-label">Sinopsis</label>
                    {{-- textareas usan old() entre las etiquetas --}}
                    <textarea class="form-control bg-dark text-white @error('synopsis') is-invalid @enderror" id="synopsis" name="synopsis" rows="5" required>{{ old('synopsis') }}</textarea>
                    @error('synopsis')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-4">
                {{-- Campo Imagen de Portada --}}
                <div class="mb-3">
                    <label for="cover_image" class="form-label">Imagen de Portada</label>
                    {{-- Añadida clase @error is-invalid --}}
                    <input type="file" class="form-control bg-dark text-white @error('cover_image') is-invalid @enderror" id="cover_image" name="cover_image">
                    <div class="form-text">Imagen recomendada: 350x500 píxeles. Límite: 5MB</div>

                    {{-- *** MODIFICADO: Mostrar error específico para cover_image *** --}}
                    @error('cover_image')
                        {{-- Cambiado a invalid-feedback y forzado display con d-block si es necesario --}}
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                    {{-- *** FIN DEL BLOQUE MODIFICADO *** --}}

                    {{-- Busca aquí si tienes escrito <div class="text-danger">The cover image failed to upload.</div> y BÓRRALO --}}

                </div>

                {{-- Vista Previa --}}
                <div class="mb-3">
                    <div class="card bg-dark">
                        <div class="card-body">
                            <h5 class="card-title">Vista previa</h5>
                            {{-- Asegúrate que esta imagen exista o el 404 seguirá apareciendo --}}
                            <img id="cover_preview" src="/images/default-cover.jpg" class="img-fluid" alt="Vista previa">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Guardar Serie</button>
            <a href="{{ route('admin.series.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
@endsection

@section('scripts')
<script>
    // Tu script de vista previa está bien, no necesita cambios
    document.getElementById('cover_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('cover_preview').src = e.target.result;
            }
            reader.readAsDataURL(file);
        } else {
             // Opcional: volver a la imagen por defecto si deseleccionan
             document.getElementById('cover_preview').src = '/images/default-cover.jpg';
        }
    });
</script>
@endsection