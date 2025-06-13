<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Series;
use App\Models\Chapter;
use App\Models\Page;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Log::info('=== INICIANDO SEEDER CON IMÁGENES BASE64 ===');
        
        // LIMPIAR DATOS EXISTENTES PRIMERO (PostgreSQL compatible)
        Log::info('Limpiando datos existentes...');
        Page::query()->delete();
        Chapter::query()->delete();
        Series::query()->delete();
        User::where('email', '!=', 'admin@example.com')->delete();
        Log::info('Datos limpiados correctamente');

        // Crear usuario administrador (solo si no existe)
        Log::info('Creando usuario admin...');
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]
        );
        Log::info('Usuario admin creado/encontrado');

        // Imágenes Base64 pequeñas de placeholder (1x1 pixel PNG)
        $placeholderImage = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==';
        
        // Diferentes colores para distinguir las series
        $dragonForestCover = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8/5+hHgAHggJ/PchI7wAAAABJRU5ErkJggg=='; // Verde
        $celestialSagaCover = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg=='; // Azul
        $phantomSeekerCover = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYGBgAAAABQABh6FO1AAAAABJRU5ErkJggg=='; // Negro

        // Series con datos completos para TFG - Usando Base64
        $seriesData = [
            // SERIE 1: Dragon Forest - 3 capítulos (4, 5, 5 páginas)
            [
                'name' => 'Dragon Forest',
                'author' => 'Anónimo',
                'artist' => 'Anónimo',
                'genre' => 'Fantasy, Adventure, Action',
                'synopsis' => 'En un mundo donde los dragones y los humanos coexisten, una joven aventurera debe descubrir los secretos del Bosque de los Dragones para salvar su aldea de una antigua maldición. Armada con su determinación y una misteriosa conexión con estas criaturas legendarias.',
                'status' => 'Ongoing',
                'cover_image' => $dragonForestCover,
                'chapters' => [
                    [
                        'number' => 1,
                        'title' => 'El Llamado del Bosque',
                        'pages' => [$placeholderImage, $placeholderImage, $placeholderImage, $placeholderImage]
                    ],
                    [
                        'number' => 2,
                        'title' => 'Primer Encuentro',
                        'pages' => [$placeholderImage, $placeholderImage, $placeholderImage, $placeholderImage, $placeholderImage]
                    ],
                    [
                        'number' => 3,
                        'title' => 'El Dragón Guardián',
                        'pages' => [$placeholderImage, $placeholderImage, $placeholderImage, $placeholderImage, $placeholderImage]
                    ]
                ]
            ],
            // SERIE 2: Celestial Saga - 3 capítulos (5, 5, 5 páginas)
            [
                'name' => 'Celestial Saga',
                'author' => 'Anónimo',
                'artist' => 'Anónimo',
                'genre' => 'Fantasy, Adventure, Action',
                'synopsis' => 'Una guerrera celestial desciende del reino divino para proteger el mundo mortal de las fuerzas oscuras que amenazan con destruir el equilibrio entre el cielo y la tierra. Con su espada sagrada y sus alas angelicales, debe enfrentar enemigos que desafían tanto su fe como su poder.',
                'status' => 'Ongoing',
                'cover_image' => $celestialSagaCover,
                'chapters' => [
                    [
                        'number' => 1,
                        'title' => 'Descenso Divino',
                        'pages' => [$placeholderImage, $placeholderImage, $placeholderImage, $placeholderImage, $placeholderImage]
                    ],
                    [
                        'number' => 2,
                        'title' => 'La Espada Sagrada',
                        'pages' => [$placeholderImage, $placeholderImage, $placeholderImage, $placeholderImage, $placeholderImage]
                    ],
                    [
                        'number' => 3,
                        'title' => 'Batalla en los Cielos',
                        'pages' => [$placeholderImage, $placeholderImage, $placeholderImage, $placeholderImage, $placeholderImage]
                    ]
                ]
            ],
            // SERIE 3: Phantom Seeker - 1 capítulo (5 páginas)
            [
                'name' => 'Phantom Seeker',
                'author' => 'Anónimo',
                'artist' => 'Anónimo',
                'genre' => 'Supernatural, Action, Mystery',
                'synopsis' => 'En un mundo donde los espíritus malignos acechan en las sombras, un joven cazador de fantasmas armado con armas especiales debe enfrentar las criaturas más peligrosas del más allá. Cada misión lo acerca más a descubrir la verdad sobre su pasado y el origen de sus poderes sobrenaturales.',
                'status' => 'Completed',
                'cover_image' => $phantomSeekerCover,
                'chapters' => [
                    [
                        'number' => 1,
                        'title' => 'El Primer Contacto',
                        'pages' => [$placeholderImage, $placeholderImage, $placeholderImage, $placeholderImage, $placeholderImage]
                    ]
                ]
            ]
        ];

        Log::info('Iniciando creación de series con imágenes Base64...');
        
        // Crear las series con sus capítulos y páginas
        foreach ($seriesData as $index => $serieData) {
            Log::info("Creando serie: " . $serieData['name']);
            
            $chapters = $serieData['chapters'];
            unset($serieData['chapters']); // Remover chapters del array principal
            
            // Crear la serie
            $serie = Series::create($serieData);
            Log::info("Serie creada con ID: " . $serie->id);
            
            // Crear capítulos y páginas para cada serie
            foreach ($chapters as $chapterData) {
                Log::info("Creando capítulo: " . $chapterData['title']);
                
                $pages = $chapterData['pages'];
                unset($chapterData['pages']); // Remover pages del array de capítulo
                
                // Crear el capítulo
                $chapter = $serie->chapters()->create([
                    'number' => $chapterData['number'],
                    'title' => $chapterData['title']
                ]);
                
                // Crear las páginas con imágenes Base64
                foreach ($pages as $pageIndex => $imageBase64) {
                    $chapter->pages()->create([
                        'page_number' => $pageIndex + 1,
                        'image_path' => $imageBase64
                    ]);
                }
                
                Log::info("Capítulo creado con " . count($pages) . " páginas Base64");
            }
        }
        
        Log::info('=== SEEDER COMPLETADO CON IMÁGENES BASE64 ===');
    }
}
