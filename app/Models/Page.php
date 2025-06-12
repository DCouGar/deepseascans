<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Import for type hinting

class Page extends Model
{
    use HasFactory; // Enables factory creation for testing/seeding

    /**
     * The attributes that are mass assignable.
     * These fields can be filled using Page::create() or $page->update().
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'chapter_id',  // Foreign key linking to the Chapter model
        'page_number', // The sequential number of the page within the chapter
        'image_path',  // The path to the image file (relative to storage/app/public)
    ];

    /**
     * Get the chapter that this page belongs to.
     * Defines the inverse of the one-to-many relationship (Chapter has many Pages).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function chapter(): BelongsTo
    {
        // A page belongs to one Chapter (references 'id' on Chapter model by default)
        return $this->belongsTo(Chapter::class);
    }
}