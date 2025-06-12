@extends('layouts.manga-layout')

@section('title', __('Admin Panel') . ' - deepseaScans') {{-- Using translation --}}

@section('content')
    {{-- Use translation helper for heading --}}
    <h1 class="mb-4">{{ __('Admin Panel') }}</h1>

    <div class="row">
        {{-- Series Count Card --}}
        <div class="col-md-6 mb-4">
            {{-- Added custom class 'admin-card' for specific styling --}}
            <div class="card admin-card text-white h-100"> {{-- Added h-100 for equal height --}}
                <div class="card-body d-flex flex-column"> {{-- Flex column for alignment --}}
                    <h5 class="card-title">{{ __('Series') }}</h5>
                    {{-- Display the count passed from the controller --}}
                    <p class="card-text display-4 my-auto text-center">{{ $seriesCount ?? 0 }}</p> {{-- Default to 0 if not passed --}}
                    {{-- Use secondary button style for management actions --}}
                    <a href="{{ route('admin.series.index') }}" class="btn btn-secondary mt-auto">{{ __('Manage Series') }}</a>
                </div>
            </div>
        </div>

        {{-- Chapters Count Card --}}
        <div class="col-md-6 mb-4">
            <div class="card admin-card text-white h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ __('Chapters') }}</h5>
                    <p class="card-text display-4 my-auto text-center">{{ $chaptersCount ?? 0 }}</p>
                    {{-- TODO: This route likely needs to point to a chapter management page --}}
                    <a href="{{ route('admin.chapters.index.all') }}" class="btn btn-secondary mt-auto">{{ __('Manage Chapters') }}</a>
                     {{-- Example route: <a href="{{ route('admin.chapters.index') }}" class="btn btn-secondary mt-auto">{{ __('Manage Chapters') }}</a> --}}
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions Card --}}
    <div class="row mt-3"> {{-- Added some margin top --}}
        <div class="col-12">
            <div class="card admin-card text-white">
                <div class="card-body">
                    <h5 class="card-title">{{ __('Quick Actions') }}</h5>
                    <div class="d-flex gap-2">
                        {{-- Use primary button style for creation actions --}}
                        <a href="{{ route('admin.series.create') }}" class="btn btn-primary">{{ __('Add New Serie') }}</a>
                        {{-- Add other quick actions here if needed --}}
                        {{-- <a href="{{ route('admin.chapters.create') }}" class="btn btn-primary">{{ __('Add New Chapter') }}</a> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- Optional: Add specific styles for admin cards if needed --}}
@push('styles')
<style>
    .admin-card .display-4 {
        font-weight: 500; /* Make numbers slightly bolder */
    }
</style>
@endpush