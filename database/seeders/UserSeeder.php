<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class UserSeeder extends Seeder
{
    public function run(): void
    {
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

        $colors = [
            [231, 76, 60],   // rojo
            [46, 204, 113],  // verde
            [52, 152, 219],  // azul
            [155, 89, 182],  // morado
            [241, 196, 15],  // amarillo
            [230, 126, 34],  // naranja
            [26, 188, 156],  // turquesa
            [236, 64, 122],  // rosa
            [0, 188, 212],   // cyan
            [103, 58, 183],  // deep purple
        ];

        $storagePath = Storage::disk('public')->path('images');

        foreach ($contacts as $i => $data) {
            $user = User::create([
                'name'  => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
            ]);

            if ($data['photo']) {
                $color = $colors[$i % count($colors)];
                $fileName = 'seed_' . strtolower(str_replace(' ', '_', $data['name'])) . '_' . time() . $i . '.jpg';
                $filePath = $storagePath . '/' . $fileName;

                $this->generatePlaceholderImage($filePath, $data['name'], $color);

                $user->images()->create([
                    'url' => \Illuminate\Support\Facades\Storage::disk('public')->url('images/' . $fileName),
                ]);
            }
        }
    }

    private function generatePlaceholderImage(string $path, string $name, array $color): void
    {
        $width = 400;
        $height = 400;
        $img = imagecreatetruecolor($width, $height);

        $bg = imagecolorallocate($img, $color[0], $color[1], $color[2]);
        imagefill($img, 0, 0, $bg);

        $initials = '';
        $parts = explode(' ', $name);
        foreach ($parts as $part) {
            if (!empty($part[0])) {
                $initials .= mb_strtoupper($part[0]);
            }
        }
        $initials = mb_substr($initials, 0, 2);

        $white = imagecolorallocate($img, 255, 255, 255);
        $fontSize = 5;
        $textWidth = imagefontwidth($fontSize) * strlen($initials);
        $textHeight = imagefontheight($fontSize);
        $x = (int)(($width - $textWidth) / 2);
        $y = (int)(($height - $textHeight) / 2);
        imagestring($img, $fontSize, $x, $y, $initials, $white);

        imagejpeg($img, $path, 85);
        imagedestroy($img);
    }
}
