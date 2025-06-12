<?php

namespace Tests\Feature;

use App\Models\Series;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SeriesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test ID: 001
     * Objetivo: Verificar que un usuario invitado puede ver la lista de series
     * Resultado Esperado: Código de estado 200 y vista correcta
     */
    public function test_guest_can_view_landing_page_with_series()
    {
        $series = Series::factory()->count(3)->create();

        $response = $this->get(route('landing'));

        $response->assertStatus(200);
        $response->assertViewIs('landing');
        foreach ($series as $s) {
            $response->assertSee($s->name);
        }
    }

    /**
     * Test ID: 002
     * Objetivo: Verificar que un usuario invitado puede ver los detalles de una serie
     * Resultado Esperado: Código de estado 200 y vista correcta
     */
    public function test_guest_can_view_series_details()
    {
        $series = Series::factory()->create();

        $response = $this->get(route('series.show.public', $series));

        $response->assertStatus(200);
        $response->assertViewIs('series.show');
        $response->assertSee($series->name);
        $response->assertSee($series->author);
        $response->assertSee($series->artist);
        $response->assertSee($series->genre);
        $response->assertSee($series->synopsis);
    }

    /**
     * Test ID: 003
     * Objetivo: Verificar que un usuario invitado no puede acceder al panel de administración
     * Resultado Esperado: Redirección al login
     */
    public function test_guest_cannot_access_admin_panel()
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test ID: 004
     * Objetivo: Verificar que el admin puede crear una nueva serie
     * Resultado Esperado: Serie creada y redirección a la lista de series
     */
    public function test_admin_can_create_series()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $seriesData = [
            'name' => 'Test Series',
            'author' => 'Test Author',
            'artist' => 'Test Artist',
            'genre' => 'Test Genre',
            'synopsis' => 'Test Synopsis',
            'status' => 'Ongoing'
        ];

        $response = $this->actingAs($admin)
            ->post(route('admin.series.store'), $seriesData);

        $response->assertRedirect(route('admin.series.index'));
        $this->assertDatabaseHas('series', $seriesData);
    }

    /**
     * Test ID: 005
     * Objetivo: Verificar que el admin puede actualizar una serie
     * Resultado Esperado: Serie actualizada y redirección a la lista de series
     */
    public function test_admin_can_update_series()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $series = Series::factory()->create();
        $updateData = [
            'name' => 'Updated Series',
            'author' => 'Updated Author',
            'artist' => 'Updated Artist',
            'genre' => 'Updated Genre',
            'synopsis' => 'Updated Synopsis',
            'status' => 'Completed'
        ];

        $response = $this->actingAs($admin)
            ->put(route('admin.series.update', $series), $updateData);

        $response->assertRedirect(route('admin.series.index'));
        $this->assertDatabaseHas('series', $updateData);
    }

    /**
     * Test ID: 006
     * Objetivo: Verificar que el admin puede eliminar una serie
     * Resultado Esperado: Serie eliminada y redirección a la lista de series
     */
    public function test_admin_can_delete_series()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $series = Series::factory()->create();

        $response = $this->actingAs($admin)
            ->delete(route('admin.series.destroy', $series));

        $response->assertRedirect(route('admin.series.index'));
        $this->assertDatabaseMissing('series', ['id' => $series->id]);
    }

    public function test_series_are_ordered_correctly()
    {
        // Crear series con nombres específicos
        $seriesC = Series::factory()->create(['name' => 'C Series']);
        $seriesA = Series::factory()->create(['name' => 'A Series']);
        $seriesB = Series::factory()->create(['name' => 'B Series']);

        $response = $this->get(route('landing'));

        $response->assertStatus(200);
        $response->assertViewIs('landing');
        // Verificar que los nombres aparecen en el orden correcto en el HTML
        $content = $response->getContent();
        $first = strpos($content, 'A Series');
        $second = strpos($content, 'B Series');
        $third = strpos($content, 'C Series');
        $this->assertTrue($first !== false && $second !== false && $third !== false);
        $this->assertTrue($first < $second && $second < $third);
    }

    public function test_series_cover_image_upload_works()
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);
        
        $response = $this->actingAs($admin)->post(route('admin.series.store'), [
            'name' => 'Test Series',
            'author' => 'Test Author',
            'artist' => 'Test Artist',
            'genre' => 'Test Genre',
            'synopsis' => 'Test Synopsis',
            'status' => 'Ongoing',
            'cover_image' => UploadedFile::fake()->image('cover.jpg')
        ]);

        $response->assertRedirect(route('admin.series.index'));
        
        // Verificar que la imagen se guardó
        $series = Series::where('name', 'Test Series')->first();
        $this->assertNotNull($series->cover_image);
        $this->assertTrue(Storage::disk('public')->exists($series->cover_image));
    }

    public function test_series_name_must_be_unique()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        
        // Crear una serie inicial
        Series::factory()->create(['name' => 'Test Series']);

        // Intentar crear otra serie con el mismo nombre
        $response = $this->actingAs($admin)->post(route('admin.series.store'), [
            'name' => 'Test Series',
            'author' => 'Test Author',
            'artist' => 'Test Artist',
            'genre' => 'Test Genre',
            'synopsis' => 'Test Synopsis',
            'status' => 'Ongoing'
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_series_status_must_be_valid()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        
        $response = $this->actingAs($admin)->post(route('admin.series.store'), [
            'name' => 'Test Series',
            'author' => 'Test Author',
            'artist' => 'Test Artist',
            'genre' => 'Test Genre',
            'synopsis' => 'Test Synopsis',
            'status' => 'Invalid Status'
        ]);

        $response->assertSessionHasErrors('status');
    }
} 