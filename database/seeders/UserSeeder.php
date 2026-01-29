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
        $user1->number_documment = 123456789;
        $user1->name = 'Sebastian';
        $user1->last_name = 'Ramirez';
        $user1->id_state = 1;
        $user1->email = 'prueba.@gmail.com';
        $user1->password = bcrypt('123');
        $user1->signature = null;
        $user1->assignRole('coordinador');
        $user1->save();
    }
}
