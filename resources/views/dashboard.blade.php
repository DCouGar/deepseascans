@extends('layouts.manga-layout')

@section('title', 'Panel de Administración - deepseaScans')

@section('content')
    <h1 class="mb-4">Panel de Administración</h1>
    
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card bg-dark text-white">
                <div class="card-body">
                    <h5 class="card-title">Series</h5>
                    <p class="card-text display-4">{{ $seriesCount }}</p>
                    <a href="{{ route('admin.series.index') }}" class="btn btn-outline-light">Gestionar Series</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card bg-dark text-white">
                <div class="card-body">
                    <h5 class="card-title">Capítulos</h5>
                    <p class="card-text display-4">{{ $chaptersCount }}</p>
                    <a href="{{ route('admin.series.index') }}" class="btn btn-outline-light">Gestionar Capítulos</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card bg-dark text-white">
                <div class="card-body">
                    <h5 class="card-title">Acciones Rápidas</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.series.create') }}" class="btn btn-primary">Añadir Nueva Serie</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
