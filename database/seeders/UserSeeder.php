<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Users_teacher;

class UserSeeder extends Seeder
{
    public function run(): void
    {

        $user1 = new Users_teacher;
        $user1->number_documment = 2222222222;
        $user1->name = 'María';
        $user1->last_name = 'Antonia';
        $user1->id_state = 1;
        $user1->email = 'c.academica@bethlemitaspereira.edu.co';
        $user1->password = bcrypt('2222222222');
        $user1->signature = null;

        // primero guardar
        $user1->save();

        // luego asignar rol
        $user1->assignRole('coordinador');
    }
}