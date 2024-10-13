<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'=>$this->faker->word,
            'description'=>$this->faker->sentence,
            'stock'=>$this->faker->numberBetween(1, 100),
            'unit_of_measure'=>$this->faker->randomElement(['mg', 'g', 'kg', 'tonelada', 'ml', 'l', 'kl']),
            'price'=>$this->faker->randomFloat(2, 10, 1000),
            'category_id'=>Category::factory(),
            'supplier_id'=>Supplier::factory(),
        ];
    }
}
