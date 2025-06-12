<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Series;
use App\Models\Chapter;
use App\Models\Page;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear usuario administrador
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        // Crear algunas series de ejemplo
        $series = [
            [
                'name' => 'Bad Born Blood',
                'author' => 'hobak bird',
                'artist' => 'D-park',
                'genre' => 'Action, Adventure, Fantasy, Sci-fi',
                'synopsis' => 'Humanity\'s home is no longer Earth. Interstellar travel is now a common occurrence, and technology has replaced blood and flesh. Luka, who grew up in the slums, has turned fifteen and taken the selection exams. His aptitude is of the "Imperial Guard."',
                'status' => 'Ongoing',
            ],
            [
                'name' => 'Duke Pendragon',
                'author' => 'Chwiryong',
                'artist' => 'Carrot Soup',
                'genre' => 'Action, Adventure, Fantasy',
                'synopsis' => 'A story about a man who became possessed by the greatest dragon slayer.',
                'status' => 'Ongoing',
            ],
            [
                'name' => 'The Nebula\'s Civilization',
                'author' => 'Miso',
                'artist' => 'Miso Studio',
                'genre' => 'Action, Adventure, Sci-fi',
                'synopsis' => 'In a distant future, humanity has spread across the stars, forming a vast civilization spanning the nebula.',
                'status' => 'Ongoing',
            ],
        ];

        foreach ($series as $seriesData) {
            $serie = Series::create($seriesData);
            
            // Crear algunos capítulos para cada serie
            for ($i = 1; $i <= 5; $i++) {
                $chapter = Chapter::create([
                    'series_id' => $serie->id,
                    'number' => $i,
                    'title' => 'Capítulo ' . $i,
                ]);
                
                // Crear páginas de ejemplo para cada capítulo
                for ($j = 1; $j <= 3; $j++) {
                    Page::create([
                        'chapter_id' => $chapter->id,
                        'page_number' => $j,
                        'image_path' => 'placeholder/page' . $j . '.jpg',
                    ]);
                }
            }
        }
    }
}
