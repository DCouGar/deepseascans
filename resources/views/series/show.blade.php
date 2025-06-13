@extends('layouts.manga-layout')

@section('title', $series->name . ' - deepseaScans')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <img src="{{ $series->cover_image ? '/storage/' . $series->cover_image : '/images/default-cover.jpg' }}" 
                 alt="{{ $series->name }}" class="img-fluid rounded mb-3">
                 
            <div class="d-grid gap-2 mb-3">
                <a href="{{ route('landing') }}" class="btn btn-outline-light">Volver a inicio</a>
                @if($series->latestChapter())
                    <a href="{{ route('chapters.show', [$series->id, $series->latestChapter()->number]) }}" 
                       class="btn btn-primary">Leer último capítulo</a>
                @endif
                @if($series->firstChapter())
                    <a href="{{ route('chapters.show', [$series->id, $series->firstChapter()->number]) }}" 
                       class="btn btn-secondary">Leer desde el principio</a>
                @endif
            </div>
        </div>
        
        <div class="col-md-8">
            <h1>{{ $series->name }}</h1>
            
            <div class="mb-3 p-3 border border-white rounded">
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
            </div>
            
            <div class="mb-4">
                <h4>Sinopsis</h4>
                <p>{{ $series->synopsis }}</p>
            </div>
            
            <h4>Capítulos</h4>
            <ul class="chapter-list">
                @forelse($series->chapters()->orderBy('number', 'desc')->get() as $chapter)
                    <li>
                        <a href="{{ route('chapters.show', [$series->id, $chapter->number]) }}">
                            Capítulo {{ $chapter->number }}
                            @if($chapter->title) - {{ $chapter->title }} @endif
                        </a>
                    </li>
                @empty
                    <li>No hay capítulos disponibles.</li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection
