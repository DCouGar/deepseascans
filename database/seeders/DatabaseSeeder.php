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
        Log::info('=== INICIANDO SEEDER NUEVO ===');
        
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

        // Series con datos completos para TFG - Especificaciones exactas del usuario
        $seriesData = [
            // SERIE 1: Dragon Forest - 3 capítulos (4, 5, 5 páginas)
            [
                'name' => 'Dragon Forest',
                'author' => 'Anónimo',
                'artist' => 'Anónimo',
                'genre' => 'Fantasy, Adventure, Action',
                'synopsis' => 'En un mundo donde los dragones y los humanos coexisten, una joven aventurera debe descubrir los secretos del Bosque de los Dragones para salvar su aldea de una antigua maldición. Armada con su determinación y una misteriosa conexión con estas criaturas legendarias.',
                'status' => 'Ongoing',
                'cover_image' => 'covers/dragon-forest-cover.png',
                'chapters' => [
                    [
                        'number' => 1,
                        'title' => 'El Llamado del Bosque',
                        'pages' => [
                            'series/1/chapters/1/page-1.png',
                            'series/1/chapters/1/page-2.png',
                            'series/1/chapters/1/page-3.png',
                            'series/1/chapters/1/page-4.png',
                        ]
                    ],
                    [
                        'number' => 2,
                        'title' => 'Primer Encuentro',
                        'pages' => [
                            'series/1/chapters/2/page-1.png',
                            'series/1/chapters/2/page-2.png',
                            'series/1/chapters/2/page-3.png',
                            'series/1/chapters/2/page-4.png',
                            'series/1/chapters/2/page-5.png',
                        ]
                    ],
                    [
                        'number' => 3,
                        'title' => 'El Dragón Guardián',
                        'pages' => [
                            'series/1/chapters/3/page-1.png',
                            'series/1/chapters/3/page-2.png',
                            'series/1/chapters/3/page-3.png',
                            'series/1/chapters/3/page-4.png',
                            'series/1/chapters/3/page-5.png',
                        ]
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
                'cover_image' => 'covers/celestial-saga-cover.png',
                'chapters' => [
                    [
                        'number' => 1,
                        'title' => 'Descenso Divino',
                        'pages' => [
                            'series/2/chapters/1/page-1.png',
                            'series/2/chapters/1/page-2.png',
                            'series/2/chapters/1/page-3.png',
                            'series/2/chapters/1/page-4.png',
                            'series/2/chapters/1/page-5.png',
                        ]
                    ],
                    [
                        'number' => 2,
                        'title' => 'La Espada Sagrada',
                        'pages' => [
                            'series/2/chapters/2/page-1.png',
                            'series/2/chapters/2/page-2.png',
                            'series/2/chapters/2/page-3.png',
                            'series/2/chapters/2/page-4.png',
                            'series/2/chapters/2/page-5.png',
                        ]
                    ],
                    [
                        'number' => 3,
                        'title' => 'Batalla en los Cielos',
                        'pages' => [
                            'series/2/chapters/3/page-1.png',
                            'series/2/chapters/3/page-2.png',
                            'series/2/chapters/3/page-3.png',
                            'series/2/chapters/3/page-4.png',
                            'series/2/chapters/3/page-5.png',
                        ]
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
                'cover_image' => 'covers/phantom-seeker-cover.png',
                'chapters' => [
                    [
                        'number' => 1,
                        'title' => 'El Primer Contacto',
                        'pages' => [
                            'series/3/chapters/1/page-1.png',
                            'series/3/chapters/1/page-2.png',
                            'series/3/chapters/1/page-3.png',
                            'series/3/chapters/1/page-4.png',
                            'series/3/chapters/1/page-5.png',
                        ]
                    ]
                ]
            ]
        ];

        Log::info('Iniciando creación de series...');
        
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
                
                // Crear las páginas
                foreach ($pages as $pageIndex => $imagePath) {
                    $chapter->pages()->create([
                        'page_number' => $pageIndex + 1,
                        'image_path' => $imagePath
                    ]);
                }
                
                Log::info("Capítulo creado con " . count($pages) . " páginas");
            }
        }
        
        Log::info('=== SEEDER COMPLETADO EXITOSAMENTE ===');
    }
}
