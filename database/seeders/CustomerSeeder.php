<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use Faker\Factory as Faker;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Create 20 customers
        for ($i = 1; $i <= 20; $i++) {
            Customer::create([
                'customer_code' => 'CUST' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'phone' => $faker->phoneNumber(),
                'address' => $faker->address(),
                'city' => $faker->city(),
                'total_purchases' => $faker->randomFloat(2, 0, 50000000),
                'status' => $faker->randomElement(['Active', 'Inactive']),
            ]);
        }
    }
}
