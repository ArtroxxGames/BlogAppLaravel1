<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'title' => rtrim(fake()->unique()->sentence(6), '.'),
            'excerpt' => fake()->paragraph(2),
            'body' => $this->markdownBody(),
            'published_at' => fake()->dateTimeBetween('-6 months', '-1 hour'),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => ['published_at' => null]);
    }

    public function scheduled(): static
    {
        return $this->state(fn (array $attributes) => ['published_at' => now()->addWeek()]);
    }

    private function markdownBody(): string
    {
        $sections = [];

        foreach (range(1, 3) as $i) {
            $sections[] = '## '.rtrim(fake()->sentence(4), '.');
            $sections[] = fake()->paragraphs(2, true);
        }

        $sections[] = '- '.implode("\n- ", fake()->sentences(3));
        $sections[] = '> '.fake()->sentence(12);

        return implode("\n\n", $sections);
    }
}
