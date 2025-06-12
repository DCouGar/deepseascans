<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Series;
use App\Models\Page; // Ensure Page model is used if needed, otherwise remove
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Facades\Log; // Only uncomment if you need detailed logging

class ChapterController extends Controller
{
    // --- Public Method ---

    /**
     * Display a specific chapter for public viewing.
     * Shows chapter pages and navigation to previous/next chapter.
     */
    public function show(Series $series, $chapterNumber)
    {
        // Find the specific chapter by series ID and chapter number
        $chapter = Chapter::where('series_id', $series->id)
                        ->where('number', $chapterNumber)
                        ->firstOrFail(); // Show 404 if chapter not found

        // Get all pages for this chapter, ordered by their page number
        $pages = $chapter->pages()->orderBy('page_number', 'asc')->get();

        // Find the previous chapter within the same series
        $previousChapter = Chapter::where('series_id', $series->id)
                                ->where('number', '<', $chapter->number)
                                ->orderBy('number', 'desc') // Get the highest number lower than current
                                ->first();

        // Find the next chapter within the same series
        $nextChapter = Chapter::where('series_id', $series->id)
                                ->where('number', '>', $chapter->number)
                                ->orderBy('number', 'asc') // Get the lowest number higher than current
                                ->first();

        // Send all data to the 'chapters.show' view
        return view('chapters.show', compact('series', 'chapter', 'pages', 'previousChapter', 'nextChapter'));
    }

    // --- Admin Methods ---

    /**
     * Show the form for creating a new chapter for a specific series.
     */
    public function create(Series $series)
    {
        // Pass the specific series to the 'admin.chapters.create' view
        return view('admin.chapters.create', compact('series'));
    }

    /**
     * Display a paginated list of ALL chapters from ALL series in the admin panel.
     */
    public function adminAllChaptersIndex()
    {
        // Get all chapters, load their series information to prevent extra queries
        $chapters = Chapter::with('series') // Eager load the 'series' relationship
                           ->orderBy('series_id', 'asc')
                           ->orderBy('number', 'desc')
                           ->paginate(25); // Show 25 chapters per page

        // Pass the paginated chapters collection to the 'admin.chapters.index_all' view
        return view('admin.chapters.index_all', compact('chapters'));
    }

    /**
     * Display a paginated list of chapters for a SPECIFIC series in the admin panel.
     */
    public function adminIndex(Series $series)
    {
        // Get chapters belonging only to the passed $series model
        $chapters = $series->chapters() // Use the relationship defined in the Series model
                           ->orderBy('number', 'desc')
                           ->paginate(15); // Show 15 chapters per page

        // Pass the specific series and its paginated chapters to the 'admin.chapters.index' view
        return view('admin.chapters.index', compact('series', 'chapters'));
    }

    /**
     * Store a newly created chapter and its pages.
     */
    public function store(Request $request, Series $series)
    {
        // Validate the form data
        $validated = $request->validate([
            'number' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) use ($series) {
                    if ($series->chapters()->where('number', $value)->exists()) {
                        $fail('Ya existe un capítulo con este número en esta serie.');
                    }
                },
            ],
            'title' => 'nullable|string|max:255',
            'pages' => 'required|array|min:1', // At least one page is required
            'pages.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120' // Max 5MB per image
        ]);

        // Create the new chapter associated with the series
        $chapter = $series->chapters()->create([
            'number' => $validated['number'],
            'title' => $validated['title'] // Stored as null if empty
        ]);

        // Process uploaded page images
        if ($request->hasFile('pages')) {
            // Define the directory path based on series and chapter
            $directory = "series/{$series->id}/chapters/{$chapter->number}";
            $pageNumber = 1; // Start page numbering at 1

            // Process each uploaded image file
            foreach ($request->file('pages') as $image) {
                // Store the image in 'storage/app/public/{directory}'
                $path = $image->store($directory, 'public');

                // If storing failed, delete the partially created chapter and show error
                if (!$path) {
                     $chapter->delete(); // Clean up chapter record
                     return back()->withErrors(['pages' => 'Error saving one of the images.'])->withInput();
                }

                // Create a database record for the page
                $chapter->pages()->create([
                    'page_number' => $pageNumber,
                    'image_path' => $path
                ]);
                $pageNumber++; // Go to the next page number
            }
        } else {
             // If validation passed but somehow no files are present, clean up and show error
             $chapter->delete();
             return back()->withErrors(['pages' => 'No page files were uploaded.'])->withInput();
        }

        // Redirect to the chapter list for this series after successful creation
        return redirect()->route('admin.chapters.index', $series->id)->with('success', 'Chapter created successfully.');
    }

    /**
     * Show the form for editing an existing chapter.
     */
    public function edit(Series $series, Chapter $chapter)
    {
        // Pass the specific series and chapter to the 'admin.chapters.edit' view
        return view('admin.chapters.edit', compact('series', 'chapter'));
    }

    /**
     * Update an existing chapter (details and optionally add new pages).
     */
    public function update(Request $request, Series $series, Chapter $chapter)
    {
        // Validate the form data
        $validated = $request->validate([
            'number' => 'required|integer|min:1',
            'title' => 'nullable|string|max:255',
            'new_pages' => 'nullable|array', // New pages are optional
            'new_pages.*' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif,webp|max:5120' // Validate only if present
        ]);

        // Update the chapter's number and title
        $chapter->update([
            'number' => $validated['number'],
            'title' => $validated['title']
        ]);

        // Process and add any NEW uploaded pages
        if ($request->hasFile('new_pages')) {
            $directory = "series/{$series->id}/chapters/{$chapter->number}"; // Use potentially updated chapter number
            $lastPage = $chapter->pages()->max('page_number') ?? 0; // Get the last page number
            $pageNumber = $lastPage + 1; // Start numbering after the last page

            foreach ($request->file('new_pages') as $image) {
                $path = $image->store($directory, 'public');
                 if (!$path) {
                     return back()->withErrors(['new_pages' => 'Error saving one of the new images.'])->withInput();
                }
                // Create database record for the new page
                $chapter->pages()->create([
                    'page_number' => $pageNumber,
                    'image_path' => $path
                ]);
                $pageNumber++;
            }
        }

        // Redirect to the chapter list for this series after successful update
        return redirect()->route('admin.chapters.index', $series->id)->with('success', 'Chapter updated successfully.');
    }

    /**
     * Delete a chapter and its associated page files.
     */
    public function destroy(Series $series, Chapter $chapter)
    {
        // Get all pages associated with this chapter BEFORE deleting the chapter record
        $pages = $chapter->pages()->get();

        // Delete the chapter record from the database
        // If cascading deletes are set up in the database migration,
        // this might automatically delete the associated page records too.
        $chapter->delete();

        // Loop through the pages that BELONGED to the chapter
        foreach ($pages as $page) {
            // Attempt to delete the image file from storage
            Storage::disk('public')->delete($page->image_path);
            // Manually delete page record if not using cascading deletes
            // $page->delete();
        }

        // Optionally attempt to delete the chapter directory if it's empty now
        // $directory = "series/{$series->id}/chapters/{$chapter->number}"; // Note: number might be outdated if changed before delete
        // Consider a different approach if directory cleanup is essential

        // Redirect to the chapter list for this series after successful deletion
        return redirect()->route('admin.chapters.index', $series->id)->with('success', 'Chapter deleted successfully.');
    }
}