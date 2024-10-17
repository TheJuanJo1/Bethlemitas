<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Users_teacher;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $user1 = new Users_teacher;
        $user1->number_documment = 1087986236;
        $user1->name = 'Sebastian';
        $user1->last_name = 'Ramirez';
        $user1->id_state = 2;
        $user1->email = 'wowco.2000@gmail.com';
        $user1->password = bcrypt('sebas123');
        $user1->signature = null;
        $user1->assignRole('coordinador');
        $user1->save();
    }
}
