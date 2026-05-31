<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()

    {
        // User::factory()->create([
        //     "id"=>1,
        //     'name' => 'Nziza Oscar',
        //     'email' => 'nzizaoscar25@gmail.com',
        //     'password'=>bcrypt('123abc')
        // ]);

        // Note::factory(100)->create();


        User::factory()->create([
            "id" => null,
            "name" => "kaliAB",
            "email" => "kaliAB25@gmail.com",
            "password" => bcrypt("123456789"),
            "phone" => "0785240870",
            "user" => null,
            "activation" => null,
            "gender" => 'male',
            "country" => '',
            "referee_id" => '8',
            "email_verified_at" => now(),
            "contract" => 'not',
            "has_paid_package" => 'no',
            "has_free_package" => 'no',
            "utype" => 'USR',
            "remember_token" =>null,
            "current_team_id" => null,
            "profile_photo_path" => null,
            "created_at" => now(),
            "updated_at" => now(),
            "has_request"=>""
        ]);

        // \:factory(10)->create();
    }
}
