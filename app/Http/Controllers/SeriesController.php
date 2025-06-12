<?php

namespace App\Http\Controllers;

use App\Models\Series;
// use App\Models\Chapter; // Only needed if deleting chapters manually in destroy()
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Facades\DB; // Only needed for manual transaction in destroy()

class SeriesController extends Controller
{
    /**
     * Display paginated list of series for public view.
     */
    public function index()
    {
        $series = Series::orderBy('name', 'asc')->paginate(12); // Get 12 series per page
        return view('series.index', compact('series'));
    }

    /**
     * Display a specific series and its chapters (ordered) for public view.
     */
    public function show(Series $series)
    {
        // Load chapters relationship, ordered by number
        $series->load(['chapters' => function ($query) {
            $query->orderBy('number', 'asc');
        }]);
        return view('series.show', compact('series'));
    }

    // --- Admin Methods ---

    /**
     * Display paginated list of series in the admin panel.
     * Includes chapter count for each series.
     */
    public function adminIndex()
    {
        // Get 15 series per page, with chapter count, newest first
        $series = Series::withCount('chapters')
                       ->orderBy('id', 'desc')
                       ->paginate(15);
        return view('admin.series.index', compact('series'));
    }

    /**
     * Show the form for creating a new series.
     */
    public function create()
    {
        return view('admin.series.create');
    }

    /**
     * Store a newly created series. Handles cover image upload.
     */
    public function store(Request $request)
    {
        // Validate form data (ensures name is unique)
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:series,name',
            'author' => 'required|string|max:255',
            'artist' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'synopsis' => 'required|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'status' => 'required|string|in:Ongoing,Completed'
        ]);

        // If a cover image was uploaded, store it and get the path
        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        } else {
            $validated['cover_image'] = null;
        }

        // Create the series in the database
        Series::create($validated);

        // Redirect back to the admin series list
        return redirect()->route('admin.series.index')->with('success', 'Serie creada correctamente.');
    }

    /**
     * Show the form for editing an existing series.
     */
    public function edit(Series $series)
    {
        // Pass the specific series model to the view
        return view('admin.series.edit', compact('series'));
    }

    /**
     * Update an existing series. Handles optional cover image update (deletes old image).
     */
    public function update(Request $request, Series $series)
    {
        // Validate form data (ensures name is unique, ignoring current series)
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:series,name,' . $series->id,
            'author' => 'required|string|max:255',
            'artist' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'synopsis' => 'required|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'status' => 'required|string|in:Ongoing,Completed'
        ]);

        // If a new cover image was uploaded
        if ($request->hasFile('cover_image')) {
            // Delete the old image file, if it exists
            if ($series->cover_image && Storage::disk('public')->exists($series->cover_image)) {
                Storage::disk('public')->delete($series->cover_image);
            }
            // Store the new image and update the path in validated data
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        // Update the series record in the database
        $series->update($validated);

        // Redirect back to the admin series list
        return redirect()->route('admin.series.index')->with('success', 'Serie actualizada correctamente.');
    }

    /**
     * Delete a series. Also deletes the cover image file.
     * IMPORTANT: Assumes related chapters/pages are deleted via database cascade constraints.
     */
    public function destroy(Series $series)
    {
        $coverImagePath = $series->cover_image; // Get image path before deleting record

        // Delete the series record (related chapters/pages should cascade delete)
        $series->delete();

        // Delete the cover image file from storage, if it exists
        if ($coverImagePath && Storage::disk('public')->exists($coverImagePath)) {
            Storage::disk('public')->delete($coverImagePath);
        }

        // Redirect back to the admin series list
        return redirect()->route('admin.series.index')->with('success', 'Serie eliminada correctamente.');
    }
}