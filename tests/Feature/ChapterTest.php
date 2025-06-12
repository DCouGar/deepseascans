<?php

namespace Tests\Feature;

use App\Models\Series;
use App\Models\Chapter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ChapterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test ID: 008
     * Objetivo: Verificar que un usuario no autenticado puede ver un capítulo
     * Resultado Esperado: Código de estado 200 y vista correcta
     */
    public function test_public_user_can_view_chapter()
    {
        $series = Series::factory()->create();
        $chapter = Chapter::factory()->create([
            'series_id' => $series->id,
            'number' => 1
        ]);

        $response = $this->get(route('chapters.show', [
            'series' => $series,
            'chapterNumber' => $chapter->number
        ]));

        $response->assertStatus(200);
        $response->assertViewIs('chapters.show');
    }

    /**
     * Test ID: 009
     * Objetivo: Verificar que un usuario no autenticado no puede acceder al panel de administración de capítulos
     * Resultado Esperado: Redirección al login
     */
    public function test_public_user_cannot_access_chapter_admin()
    {
        $series = Series::factory()->create();
        $response = $this->get(route('admin.chapters.index', $series));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test ID: 010
     * Objetivo: Verificar que un administrador puede crear un nuevo capítulo
     * Resultado Esperado: Capítulo creado y redirección a la lista de capítulos
     */
    public function test_admin_can_create_chapter()
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);
        $series = Series::factory()->create();
        $chapterData = [
            'title' => 'Test Chapter',
            'number' => 1,
            'pages' => [
                UploadedFile::fake()->image('page1.jpg'),
                UploadedFile::fake()->image('page2.jpg'),
            ]
        ];

        $response = $this->actingAs($admin)
            ->post(route('admin.chapters.store', $series), $chapterData);

        $response->assertRedirect(route('admin.chapters.index', $series));
        $this->assertDatabaseHas('chapters', [
            'series_id' => $series->id,
            'number' => 1,
            'title' => 'Test Chapter',
        ]);
    }

    /**
     * Test ID: 011
     * Objetivo: Verificar que un administrador puede actualizar un capítulo
     * Resultado Esperado: Capítulo actualizado y redirección a la lista de capítulos
     */
    public function test_admin_can_update_chapter()
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);
        $series = Series::factory()->create();
        $chapter = Chapter::factory()->create(['series_id' => $series->id, 'number' => 1]);
        $updateData = [
            'title' => 'Updated Chapter',
            'number' => 1,
            'new_pages' => [
                UploadedFile::fake()->image('page3.jpg'),
            ]
        ];

        $response = $this->actingAs($admin)
            ->put(route('admin.chapters.update', [$series, $chapter]), $updateData);

        $response->assertRedirect(route('admin.chapters.index', $series));
        $this->assertDatabaseHas('chapters', [
            'series_id' => $series->id,
            'number' => 1,
            'title' => 'Updated Chapter',
        ]);
    }

    /**
     * Test ID: 012
     * Objetivo: Verificar que un administrador puede eliminar un capítulo
     * Resultado Esperado: Capítulo eliminado y redirección a la lista de capítulos
     */
    public function test_admin_can_delete_chapter()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $series = Series::factory()->create();
        $chapter = Chapter::factory()->create(['series_id' => $series->id]);

        $response = $this->actingAs($admin)
            ->delete(route('admin.chapters.destroy', [$series, $chapter]));

        $response->assertRedirect(route('admin.chapters.index', $series));
        $this->assertDatabaseMissing('chapters', ['id' => $chapter->id]);
    }

    /**
     * Test ID: 013
     * Objetivo: Verificar que no se pueden crear capítulos duplicados en la misma serie
     * Resultado Esperado: Error de validación
     */
    public function test_cannot_create_duplicate_chapter_numbers()
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);
        $series = Series::factory()->create();
        $existingChapter = Chapter::factory()->create([
            'series_id' => $series->id,
            'number' => 1
        ]);

        $chapterData = [
            'title' => 'Duplicate Chapter',
            'number' => 1,
            'pages' => [
                UploadedFile::fake()->image('page1.jpg'),
            ]
        ];

        $response = $this->actingAs($admin)
            ->post(route('admin.chapters.store', $series), $chapterData);

        $response->assertSessionHasErrors('number');
        $this->assertDatabaseMissing('chapters', [
            'series_id' => $series->id,
            'number' => 1,
            'title' => 'Duplicate Chapter',
        ]);
    }

    public function test_chapter_pages_are_uploaded_correctly()
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);
        $series = Series::factory()->create();

        $response = $this->actingAs($admin)->post(route('admin.chapters.store', $series), [
            'number' => 1,
            'title' => 'Test Chapter',
            'pages' => [
                UploadedFile::fake()->image('page1.jpg'),
                UploadedFile::fake()->image('page2.jpg')
            ]
        ]);

        $response->assertRedirect(route('admin.chapters.index', $series));

        // Verificar que el capítulo se creó
        $chapter = Chapter::where('series_id', $series->id)
            ->where('number', 1)
            ->first();
        $this->assertNotNull($chapter);

        // Verificar que las páginas se guardaron
        $this->assertDatabaseHas('pages', [
            'chapter_id' => $chapter->id
        ]);
    }

    public function test_chapter_navigation_works()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $series = Series::factory()->create();
        
        // Crear tres capítulos
        $chapter1 = Chapter::factory()->create([
            'series_id' => $series->id,
            'number' => 1
        ]);
        $chapter2 = Chapter::factory()->create([
            'series_id' => $series->id,
            'number' => 2
        ]);
        $chapter3 = Chapter::factory()->create([
            'series_id' => $series->id,
            'number' => 3
        ]);

        // Verificar navegación desde el capítulo 2
        $response = $this->get(route('chapters.show', [
            'series' => $series,
            'chapterNumber' => $chapter2->number
        ]));

        $response->assertStatus(200);
        $response->assertViewHas('previousChapter', $chapter1);
        $response->assertViewHas('nextChapter', $chapter3);
    }

    public function test_chapter_images_are_validated()
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);
        $series = Series::factory()->create();

        $response = $this->actingAs($admin)->post(route('admin.chapters.store', $series), [
            'number' => 1,
            'title' => 'Test Chapter',
            'pages' => [
                UploadedFile::fake()->create('document.pdf', 100)
            ]
        ]);

        $response->assertSessionHasErrors('pages.*');
    }
} 