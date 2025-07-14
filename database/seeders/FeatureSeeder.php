<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    public function run()
    {
        $icons = [
            'bi bi-truck',  // Delivery icon
            'bi bi-shield-check',  // Security icon
            'bi bi-cash',  // Payments icon
            'bi bi-headset',  // Support icon
            'bi bi-gift',  // Gift icon
            'bi bi-star',  // Star/Rating icon
        ];

        foreach ($icons as $i => $icon) {
            Feature::create([
                'icon' => $icon,
                'title' => 'Feature Title ' . ($i + 1),
                'description' => 'This is a sample description for feature ' . ($i + 1) . '.'
            ]);
        }
    }
}
