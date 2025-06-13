@extends('layouts.manga-layout')

@section('title', $series->name . ' - Capítulo ' . $chapter->number . ' - deepseaScans')

@section('content')
    <div class="reader-nav mb-4"> {{-- Added margin bottom for spacing --}}
        {{-- FIX: Changed route name from 'series.show' to 'series.show.public' --}}
        <a href="{{ route('series.show.public', $series->id) }}" class="btn btn-secondary"> {{-- Changed style to secondary --}}
            « Volver a la serie {{-- Added chevron --}}
        </a>

        {{-- Group navigation buttons --}}
        <div class="btn-group" role="group" aria-label="Chapter Navigation">
            {{-- Store results to avoid multiple method calls --}}
            @php
                $previousChapter = $chapter->previousChapter();
                $nextChapter = $chapter->nextChapter();
            @endphp

            {{-- Previous Chapter Button --}}
            @if($previousChapter)
                <a href="{{ route('chapters.show', ['series' => $series->id, 'chapterNumber' => $previousChapter->number]) }}"
                   class="btn btn-primary"> {{-- Changed style to primary --}}
                    Capítulo anterior
                </a>
            @else
                 {{-- Disabled button for visual consistency --}}
                 <button type="button" class="btn btn-primary disabled" aria-disabled="true">Capítulo anterior</button>
            @endif

            {{-- Next Chapter Button --}}
            @if($nextChapter)
                <a href="{{ route('chapters.show', ['series' => $series->id, 'chapterNumber' => $nextChapter->number]) }}"
                   class="btn btn-primary"> {{-- Changed style to primary --}}
                    Capítulo siguiente
                </a>
            @else
                 {{-- Disabled button for visual consistency --}}
                 <button type="button" class="btn btn-primary disabled" aria-disabled="true">Capítulo siguiente</button>
            @endif
        </div>
    </div>

    <h1 class="text-center mb-4 h2">{{ $series->name }} - Capítulo {{ $chapter->number }}</h1> {{-- Reduced heading size slightly --}}

    <div class="reader-container">
        @forelse($chapter->pages()->orderBy('page_number')->get() as $page) {{-- Ensure pages are ordered --}}
            <img src="{{ '/storage/' . $page->image_path }}"
                 alt="Página {{ $page->page_number }}"
                 class="img-fluid mb-2 d-block mx-auto"> {{-- Ensure block display and centering, reduced margin --}}
        @empty
            <div class="alert alert-warning text-center"> {{-- Centered text --}}
                No hay páginas disponibles para este capítulo.
            </div>
        @endforelse
    </div>

    {{-- Bottom Navigation (duplicate of top for convenience) --}}
    <div class="reader-nav mt-4">
        {{-- FIX: Changed route name from 'series.show' to 'series.show.public' --}}
        <a href="{{ route('series.show.public', $series->id) }}" class="btn btn-secondary">
            « Volver a la serie
        </a>

        <div class="btn-group" role="group" aria-label="Chapter Navigation Bottom">
             @if($previousChapter)
                <a href="{{ route('chapters.show', ['series' => $series->id, 'chapterNumber' => $previousChapter->number]) }}"
                   class="btn btn-primary">
                    Capítulo anterior
                </a>
            @else
                 <button type="button" class="btn btn-primary disabled" aria-disabled="true">Capítulo anterior</button>
            @endif

            @if($nextChapter)
                <a href="{{ route('chapters.show', ['series' => $series->id, 'chapterNumber' => $nextChapter->number]) }}"
                   class="btn btn-primary">
                    Capítulo siguiente
                </a>
            @else
                 <button type="button" class="btn btn-primary disabled" aria-disabled="true">Capítulo siguiente</button>
            @endif
        </div>
    </div>
@endsection