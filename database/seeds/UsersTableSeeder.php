<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // reset users table
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('users')->truncate();

        $faker = Faker\Factory::create();

        App\Models\User::create([
            'firstname' => $faker->firstName(),
            'lastname' => $faker->lastName(),
            'username' => 'testuser',
            'email' => 'first@gmail.com',
            'password' => bcrypt('12345'),
            'timezone' => 'Europe/Amsterdam',
            'bio' => $faker->text(rand(200, 250)),
        ]);

        $username = str_replace(".","",$faker->unique()->username);
        App\Models\User::create([
            'firstname' => $faker->firstName(),
            'lastname' => $faker->lastName(),
            'username' => $username,
            'email' => 'second@gmail.com',
            'password' => bcrypt('12345'),
            'bio' => $faker->text(rand(200, 250)),
        ]);

        $username = str_replace(".","",$faker->unique()->username);
        App\Models\User::create([
            'firstname' => $faker->firstName(),
            'lastname' => $faker->lastName(),
            'username' => $username,
            'email' => 'third@gmail.com',
            'password' => bcrypt('12345'),
            'bio' => $faker->text(rand(200, 250)),
        ]);

        $username = str_replace(".","",$faker->unique()->username);
        App\Models\User::create([
            'firstname' => $faker->firstName(),
            'lastname' => $faker->lastName(),
            'username' => $username,
            'email' => 'fourth@gmail.com',
            'password' => bcrypt('12345'),
            'bio' => $faker->text(rand(200, 250)),
        ]);

        $username = str_replace(".","",$faker->unique()->username);
        App\Models\User::create([
            'firstname' => $faker->firstName(),
            'lastname' => $faker->lastName(),
            'username' => $username,
            'email' => 'fifth@gmail.com',
            'password' => bcrypt('12345'),
            'bio' => $faker->text(rand(200, 250)),
        ]);

    }
}
