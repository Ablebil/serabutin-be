<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Angkut & Tenaga Fisik',
            'Antar & Transport',
            'Kebersihan & Rumah Tangga',
            'Tukang & Perbaikan',
            'Jasa Warung & Kuliner',
            'Jualan & Dagang Kecil',
            'Event & Serabutan Acara',
            'Pertanian & Outdoor',
            'Jasa Umum Lain',
        ];

        foreach ($categories as $name) {
            Category::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'is_active' => true],
            );
        }
    }
}
