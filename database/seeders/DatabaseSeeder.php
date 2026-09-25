<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /** @var list<array{string, string, string}> [categoría, título, resumen] */
    private const ARTICLES = [
        ['Programación', 'Diez hábitos que te convierten en mejor desarrollador', 'Pequeñas prácticas diarias que, sumadas, marcan la diferencia en la calidad de tu código.'],
        ['Programación', 'Qué es una API REST y cómo diseñar una correctamente', 'Recursos, verbos HTTP, códigos de estado y versionado explicados con ejemplos prácticos.'],
        ['Programación', 'Introducción a los tests automatizados', 'Por qué escribir pruebas te ahorra tiempo y cómo empezar sin morir en el intento.'],
        ['Programación', 'Git para principiantes: ramas, commits y pull requests', 'Todo lo que necesitas saber para colaborar en un proyecto sin miedo a romper nada.'],
        ['Productividad', 'La técnica Pomodoro aplicada al trabajo remoto', 'Cómo organizar tu jornada en bloques de concentración y evitar el agotamiento.'],
        ['Productividad', 'Cómo decir que no sin quedar mal', 'Proteger tu tiempo es una habilidad profesional. Te contamos cómo practicarla.'],
        ['Productividad', 'Herramientas para organizar tus tareas en 2026', 'Una comparativa honesta de aplicaciones para gestionar proyectos personales y de equipo.'],
        ['Carrera profesional', 'Cómo preparar un portafolio que consiga entrevistas', 'Qué proyectos incluir, cómo presentarlos y los errores más comunes al mostrar tu trabajo.'],
        ['Carrera profesional', 'Negociar tu salario: guía paso a paso', 'Investiga, prepara tus argumentos y aprende a responder a la primera oferta.'],
        ['Carrera profesional', 'De junior a senior: lo que nadie te cuenta', 'Las habilidades técnicas importan, pero la comunicación y el criterio importan más.'],
        ['Diseño', 'Principios básicos de diseño para desarrolladores', 'Contraste, alineación, jerarquía y espacio en blanco: cuatro ideas para mejorar tus interfaces.'],
        ['Diseño', 'Accesibilidad web: por dónde empezar', 'Hacer tu sitio usable para todas las personas es más sencillo de lo que parece.'],
        ['Inteligencia artificial', 'Cómo usar asistentes de IA para programar mejor', 'Buenas prácticas para aprovechar la IA sin dejar de entender tu propio código.'],
        ['Inteligencia artificial', 'Conceptos básicos de aprendizaje automático', 'Modelos, datos de entrenamiento y evaluación explicados sin fórmulas complicadas.'],
        ['Emprendimiento', 'Validar una idea de negocio antes de programarla', 'Entrevistas, prototipos y métricas para no construir algo que nadie quiere.'],
        ['Emprendimiento', 'Lo que aprendí lanzando mi primer producto digital', 'Errores, aciertos y lecciones de un proyecto que empezó como un experimento de fin de semana.'],
    ];

    /**
     * Datos de demostración: `php artisan migrate:fresh --seed`.
     */
    public function run(): void
    {
        $admin = User::factory()->admin()->create([
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'profession' => 'Editor en jefe',
        ]);

        $authors = User::factory(5)->create()->push($admin);
        $readers = User::factory(10)->create();

        $categories = collect([
            ['name' => 'Programación', 'is_featured' => true, 'description' => 'Lenguajes, buenas prácticas y herramientas para escribir mejor software.'],
            ['name' => 'Productividad', 'is_featured' => true, 'description' => 'Organiza tu tiempo y tu energía para rendir más con menos estrés.'],
            ['name' => 'Carrera profesional', 'is_featured' => true, 'description' => 'Búsqueda de empleo, entrevistas y crecimiento profesional.'],
            ['name' => 'Diseño', 'description' => 'Interfaces, experiencia de usuario y accesibilidad.'],
            ['name' => 'Inteligencia artificial', 'is_featured' => true, 'description' => 'Qué es, cómo funciona y cómo aprovecharla en tu día a día.'],
            ['name' => 'Emprendimiento', 'description' => 'Ideas, validación y lanzamiento de productos digitales.'],
        ])->map(fn (array $attributes) => Category::factory()->create($attributes));

        $articles = collect(self::ARTICLES)->map(fn (array $data) => Article::factory()
            ->for($authors->random(), 'author')
            ->for($categories->firstWhere('name', $data[0]))
            ->create([
                'title' => $data[1],
                'excerpt' => $data[2],
                'body' => $this->body(),
            ]));

        Article::factory(3)->draft()
            ->for($admin, 'author')
            ->for($categories->first())
            ->create();

        $articles->each(function (Article $article) use ($readers) {
            $readers->random(random_int(0, 4))->each(fn (User $reader) => Comment::factory()
                ->for($article)
                ->for($reader, 'author')
                ->create());
        });
    }

    private function body(): string
    {
        $faker = fake();

        return implode("\n\n", [
            $faker->realText(400),
            '## Por qué es importante',
            $faker->realText(500),
            '- '.implode("\n- ", [$faker->realText(60), $faker->realText(60), $faker->realText(60)]),
            '## Cómo ponerlo en práctica',
            $faker->realText(500),
            '> '.$faker->realText(120),
            '## Conclusión',
            $faker->realText(300),
        ]);
    }
}
