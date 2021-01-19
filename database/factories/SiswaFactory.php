<?php

namespace Database\Factories;

use App\Models\Siswa;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SiswaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Siswa::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nisn' => $this->faker->randomNumber(6),
            'nama' => $this->faker->firstName($gender = null|'male'|'female'),
            'gender' => $this->faker->titleMale,
            'umur' => $this->faker->randomNumber(2),
            'alamat' => $this->faker->state,
        ];
    }
}
