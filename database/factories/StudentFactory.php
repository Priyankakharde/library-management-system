<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    protected $model = \App\Models\Student::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->optional()->unique()->safeEmail,
            'roll_no' => strtoupper($this->faker->bothify('R-####')),
            'department' => $this->faker->randomElement(['CS', 'IT', 'ME', 'EE', 'CE']),
        ];
    }
}
