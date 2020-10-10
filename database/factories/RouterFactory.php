<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\model\Task;
use Faker\Generator as Faker;

$factory->define(Task::class, function (Faker $faker) {
    return [
        'sap_id' => $faker->unique()->regexify('[A-Za-z0-9]{18}'),
        'host_name' => $faker->unique()->domainName,
        'loop_back' => $faker->unique()->ipv4,
        'mac_address' => $faker->unique()->macAddress
    ];
});
