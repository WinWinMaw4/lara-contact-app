<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        User::create([
            "name"=>"Win Win Maw",
            "email" => "wwm@gmail.com",
            "password" => Hash::make('password')
        ]);
        User::create([
            "name"=>"KoLay",
            "email" => "kolay@gmail.com",
            "password" => Hash::make('password')
        ]);
        User::create([
            "name"=>"Su Su",
            "email" => "susu@gmail.com",
            "password" => Hash::make('password')
        ]);

         \App\Models\User::factory(10)->create();
         Contact::factory(200)->create();
    }
}
