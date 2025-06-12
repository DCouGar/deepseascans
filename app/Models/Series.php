<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; // Keep for error logging

class Series extends Model
{
    use HasFactory; // Enables the use of factories for seeding/testing

    /**
     * The attributes that are mass assignable.
     * Allows these fields to be set automatically when using create() or update().
     * @var array<int, string>
     */
    protected $fillable = [
        'name',        // Series title
        'author',      // Series author
        'artist',      // Series artist
        'genre',       // Series genre(s)
        'synopsis',    // Series description
        'cover_image', // Path to the cover image file in storage
        'status',      // Publication status (e.g., 'Ongoing', 'Completed')
    ];

    /**
     * Define the relationship: A Series has many Chapters.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class);
    }

    /**
     * Get the first chapter (lowest number) for this series.
     * Helper method used for direct access to the first chapter.
     * @return Chapter|null Returns Chapter model or null if no chapters exist.
     */
    public function firstChapter(): ?Chapter
    {
        return $this->chapters()->orderBy('number', 'asc')->first();
    }

    /**
     * Get the latest chapter (highest number) for this series.
     * Helper method used for direct access to the latest chapter.
     * @return Chapter|null Returns Chapter model or null if no chapters exist.
     */
    public function latestChapter(): ?Chapter
    {
        return $this->chapters()->orderBy('number', 'desc')->first();
    }

    /**
     * Actions to perform when a Series model is being deleted.
     * This uses a Model Event listener.
     */
    protected static function booted(): void
    {
        // Listen for the 'deleting' event, which fires before the DB delete happens.
        static::deleting(function (Series $series) {
            // 1. Delete the cover image file from storage if it exists.
            try {
                if ($series->cover_image && Storage::disk('public')->exists($series->cover_image)) {
                    Storage::disk('public')->delete($series->cover_image);
                }
            } catch (\Exception $e) {
                // Log potential errors during file deletion but allow process to continue.
                Log::error("Error deleting cover image for Series ID {$series->id}: " . $e->getMessage());
            }

            // 2. Trigger deletion for all associated Chapters.
            // This ensures the 'deleting' event in the Chapter model is fired for each chapter,
            // allowing it to clean up its own page files and directory.
            // NOTE: Database record deletion for chapters/pages relies on `cascadeOnDelete` in migrations.
            try {
                // Process in chunks to avoid memory issues with many chapters. Select only 'id' for efficiency.
                $series->chapters()->select('id')->chunk(100, function ($chapters) {
                    foreach ($chapters as $chapter) {
                        $chapter->delete(); // Call delete on each Chapter model instance
                    }
                });
            } catch (\Exception $e) {
                 Log::error("Error triggering chapter deletion for Series ID {$series->id}: " . $e->getMessage());
                 // Consider re-throwing ($throw $e;) if chapter cleanup failure should stop series deletion,
                 // especially if not using DB cascade constraints.
            }

            // 3. Attempt to delete the main directory for the series in storage.
            // This should run last, after chapters have attempted to delete their content.
            try {
                $seriesDirectory = "series/{$series->id}";
                if (Storage::disk('public')->exists($seriesDirectory)) {
                    // Delete the directory and all remaining contents recursively.
                    Storage::disk('public')->deleteDirectory($seriesDirectory);
                }
            } catch (\Exception $e) {
                 Log::error("Error deleting main directory for Series ID {$series->id}: " . $e->getMessage());
            }
        });
    }
}