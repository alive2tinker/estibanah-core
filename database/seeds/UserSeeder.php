<?php

use Illuminate\Database\Seeder;
use App\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create(['name' => "Abdulmalik Alsufayran", 'email' => "alsufiran@gmail.com",'password' => bcrypt('alive2tinker')]);
    }
}
