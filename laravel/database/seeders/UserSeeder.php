<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        User::create([
            'name'      => 'Atendente',
            'email'     => 'atendente@gh.com.br',
            'password'  => Hash::make('senha'),
            'type'      => 'ATENDENTE',
        ]);

        User::create([
            'name'      => 'Gerente de projetos',
            'email'     => 'gp@gh.com.br',
            'password'  => Hash::make('senha'),
            'type'      => 'GERENTE_PROJETOS',
        ]);

        User::create([
            'name'      => 'Gerente de desenvolvimento',
            'email'     => 'gd@gh.com.br',
            'password'  => Hash::make('senha'),
            'type'      => 'GERENTE_DESENVOLVIMENTO',
        ]);

        User::create([
            'name'      => 'Departamento Operacional',
            'email'     => 'do@gh.com.br',
            'password'  => Hash::make('senha'),
            'type'      => 'DEPARTAMENTO_OPERACIONAL',
        ]);
    }
}
