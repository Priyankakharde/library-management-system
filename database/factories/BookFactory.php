<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Author;

class BookFactory extends Factory
{
    protected $model = \App\Models\Book::class;

    public function definition()
    {
        // pick random existing author id when seeding (or create)
        $authorId = Author::inRandomOrder()->value('id') ?? Author::factory()->create()->id;

        return [
            'title' => $this->faker->sentence(mt_rand(2,5)),
            'author_id' => $authorId,
            'cover_path' => null,
            'isbn' => $this->faker->optional()->isbn13,
            'published_at' => $this->faker->optional()->date(),
        ];
    }
}
