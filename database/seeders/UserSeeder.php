<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $hexColors = [
            'E74C3C', '2ECC71', '3498DB', '9B59B6', 'F1C40F',
            'E67E22', '1ABC9C', 'EC407A', '00BCD4', '673AB7',
        ];

        $contacts = [
            ['name' => 'María García',     'email' => 'maria.garcia@gmail.com',    'phone' => '7711234567', 'photo' => true],
            ['name' => 'Carlos López',     'email' => 'carlos.lopez@outlook.com',  'phone' => '7229876543', 'photo' => true],
            ['name' => 'Ana Martínez',     'email' => 'ana.martinez@hotmail.com',  'phone' => '7712345678', 'photo' => true],
            ['name' => 'Roberto Díaz',     'email' => null,                        'phone' => '7223456789', 'photo' => false],
            ['name' => 'Laura Hernández',  'email' => 'laura.hern@gmail.com',      'phone' => '7714567890', 'photo' => true],
            ['name' => 'Pedro Sánchez',    'email' => null,                        'phone' => '7225678901', 'photo' => false],
            ['name' => 'Sofía Ramírez',    'email' => 'sofia.ramirez@gmail.com',   'phone' => '7716789012', 'photo' => true],
            ['name' => 'Miguel Torres',    'email' => 'miguel.torres@yahoo.com',   'phone' => '7227890123', 'photo' => true],
            ['name' => 'Isabel Flores',    'email' => null,                        'phone' => '7718901234', 'photo' => false],
            ['name' => 'Fernando Ruiz',    'email' => 'fernando.ruiz@gmail.com',   'phone' => '7229012345', 'photo' => true],
            ['name' => 'Valentina Cruz',   'email' => null,                        'phone' => '7710123456', 'photo' => false],
            ['name' => 'Diego Morales',    'email' => 'diego.morales@outlook.com', 'phone' => '7221234567', 'photo' => true],
            ['name' => 'Camila Vargas',    'email' => 'camila.vargas@gmail.com',   'phone' => '7712345098', 'photo' => false],
            ['name' => 'Javier Castillo',  'email' => null,                        'phone' => '7223456098', 'photo' => false],
            ['name' => 'Lucía Romero',     'email' => 'lucia.romero@hotmail.com',  'phone' => '7714567098', 'photo' => true],
        ];

        foreach ($contacts as $i => $data) {
            $existing = User::where('phone', $data['phone'])->first();
            if ($existing) continue;

            $user = User::create([
                'name'  => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
            ]);

            if ($data['photo']) {
                $initials = '';
                foreach (explode(' ', $data['name']) as $part) {
                    if (!empty($part[0])) {
                        $initials .= mb_strtoupper($part[0]);
                    }
                }
                $initials = mb_substr($initials, 0, 2);

                $color = $hexColors[$i % count($hexColors)];
                $url = "https://placehold.co/400x400/{$color}/FFFFFF?text={$initials}&font=roboto";

                $user->images()->create(['url' => $url]);
            }
        }
    }
}
