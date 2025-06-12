<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; // Keep for error logging

class Chapter extends Model
{
    use HasFactory; // Enables the use of factories for seeding/testing

    /**
     * The attributes that are mass assignable.
     * Allows these fields to be set automatically when using create() or update().
     * @var array<int, string>
     */
    protected $fillable = [
        'series_id', // Foreign key to the parent Series
        'number',    // Chapter number
        'title',     // Optional chapter title
    ];

    /**
     * Define the relationship: A Chapter belongs to one Series.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class);
    }

    /**
     * Define the relationship: A Chapter has many Pages.
     * Automatically orders pages when accessed via this relationship.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pages(): HasMany
    {
        return $this->hasMany(Page::class)->orderBy('page_number', 'asc');
    }

    /**
     * Helper method to find the next chapter within the same series.
     * @return Chapter|null Returns Chapter model or null if it's the last chapter.
     */
    public function nextChapter(): ?Chapter
    {
        return Chapter::where('series_id', $this->series_id)
                    ->where('number', '>', $this->number)
                    ->orderBy('number', 'asc')
                    ->first();
    }

    /**
     * Helper method to find the previous chapter within the same series.
     * @return Chapter|null Returns Chapter model or null if it's the first chapter.
     */
    public function previousChapter(): ?Chapter
    {
        return Chapter::where('series_id', $this->series_id)
                    ->where('number', '<', $this->number)
                    ->orderBy('number', 'desc')
                    ->first();
    }

    /**
     * Actions to perform when a Chapter model is being deleted.
     * This uses a Model Event listener.
     */
    protected static function booted(): void
    {
        // Listen for the 'deleting' event, which fires before the DB delete happens.
        static::deleting(function (Chapter $chapter) {
            $directory = "series/{$chapter->series_id}/chapters/{$chapter->number}";

            // 1. Delete associated page image files from storage.
            // NOTE: Assumes page *database records* are deleted via cascadeOnDelete in the pages migration.
            try {
                // Process in chunks to avoid memory issues with many pages. Select only needed columns.
                $chapter->pages()->select(['id', 'image_path'])->chunk(100, function ($pages) {
                    foreach ($pages as $page) {
                        if ($page->image_path && Storage::disk('public')->exists($page->image_path)) {
                            Storage::disk('public')->delete($page->image_path);
                        }
                        // If NOT using cascade delete on pages table, uncomment:
                        // $page->delete(); // Manually delete page DB record
                    }
                });
            } catch (\Exception $e) {
                // Log potential errors during file deletion.
                 Log::error("Error deleting page files for Chapter ID {$chapter->id}: " . $e->getMessage());
                 // Consider if this error should stop the parent Series deletion (throw $e;)
            }

            // 2. Attempt to delete the specific chapter directory from storage.
            // This step might be redundant if Series::deleting successfully deletes the parent 'series/{id}' directory.
            // However, it's kept here as a direct cleanup for the chapter's own folder.
            try {
                if (Storage::disk('public')->exists($directory)) {
                    // Attempt to delete only if empty (safer approach)
                     if (empty(Storage::disk('public')->allFiles($directory)) && empty(Storage::disk('public')->allDirectories($directory))) {
                         Storage::disk('public')->deleteDirectory($directory);
                     } else {
                          // Log if not empty, helps debug if files weren't deleted properly
                          Log::warning("Chapter directory not empty after attempting page file deletion, not deleting: " . $directory);
                     }
                }
            } catch (\Exception $e) {
                 Log::error("Error deleting directory for Chapter ID {$chapter->id}: " . $e->getMessage());
            }
        });
    }
}