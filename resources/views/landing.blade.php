@extends('layouts.manga-layout')

@section('title', 'deepseaScans - Home') {{-- Changed to Home for clarity --}}

@section('content')
    <h1 class="mb-4">Series</h1> {{-- Translated heading --}}

    <div class="manga-grid">
        {{-- Use @forelse to handle the case where $series is empty --}}
        @forelse($series as $serie)
            <div class="manga-item">
                {{-- Link wrapping the image --}}
                {{-- FIX: Changed route name from 'series.show' to 'series.show.public' --}}
                <a href="{{ route('series.show.public', $serie->id) }}">
                    {{-- The image itself with the styling class and alt text --}}
                    <img src="{{ $serie->cover_image ? asset('storage/' . $serie->cover_image) : asset('images/default-cover.jpg') }}"
                         alt="{{ $serie->name }} Cover" {{-- Added "Cover" to alt text --}}
                         class="manga-image">
                </a>
                {{-- Container for text info below the image --}}
                <div class="manga-info">
                    <h5>{{ $serie->name }}</h5>
                </div>
                {{-- Container for action buttons --}}
                <div class="manga-buttons">
                    {{-- Pre-load chapters to avoid multiple calls --}}
                    @php
                        $latest = $serie->latestChapter();
                        $first = $serie->firstChapter(); // Define $first here as well
                    @endphp

                    {{-- Latest/Current Chapter Button --}}
                    @if($latest)
                        <a href="{{ route('chapters.show', ['series' => $serie->id, 'chapterNumber' => $latest->number]) }}" class="button">
                            {{-- Text Change: Removed "Latest" --}}
                            Ch. {{ $latest->number }}
                        </a>
                    @else
                        {{-- Placeholder if no chapters are available --}}
                        <span class="button disabled" style="opacity: 0.6; cursor: not-allowed; background-color: #444;">No Chapters</span>
                    @endif

                    {{-- Chapter 1 Button --}}
                    {{-- Logic Change: Show if 'first' exists AND ('latest' doesn't exist OR first number is different from latest number) --}}
                    @if($first && (!$latest || $first->number != $latest->number))
                        <a href="{{ route('chapters.show', ['series' => $serie->id, 'chapterNumber' => $first->number]) }}" class="button">
                            Ch. 1
                        </a>
                    {{-- Add placeholder only if the first button exists but the second doesn't, to maintain alignment --}}
                    @elseif ($latest)
                         <span></span> {{-- Empty span to help justify-content space-between --}}
                    @endif
                </div>
            </div>
        @empty
             {{-- Message displayed if $series collection is empty --}}
             <p class="col-12 text-center text-muted mt-4">No series available at the moment.</p>
        @endforelse
    </div>

    {{-- Add pagination --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $series->links() }}
    </div>
@endsection