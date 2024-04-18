<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    /**
     * Define o modelo da fábrica.
     *
     * @var string
     */
    protected $model = Book::class;

    /**
     * Define os atributos padrão para o modelo.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'publication_year' => $this->faker->numberBetween(1900, date('Y')),
        ];
    }
}
