<?php

use Faker\Generator as Faker;
use Kirby\Employees\Models\Employee;
use Kirby\Novelties\Models\Novelty;
use Kirby\Novelties\Models\NoveltyType;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
 */

$factory->define(Novelty::class, function (Faker $faker) {
    return [
        'time_clock_log_id' => null,
        'employee_id' => fn () => factory(Employee::class)->create()->id,
        'novelty_type_id' => $faker->randomElement(NoveltyType::pluck('id')->all()),
        'sub_cost_center_id' => null,
        'start_at' => null,
        'end_at' => null,
        'comment' => null,
    ];
});
