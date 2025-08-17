<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Venue;

class VenueSeeder extends Seeder
{
    public function run()
    {
        $venues = [
            [
                'name' => 'National Stadium',
                'city' => 'Jakarta',
                'country' => 'Indonesia',
                'capacity' => 80000,
                'surface_type' => 'grass',
                'address' => 'Jl. Pintu Satu Senayan, Jakarta Pusat',
                'phone' => '+62-21-573-1640',
                'email' => 'info@nationalstadium.id',
                'website' => 'https://nationalstadium.id',
                'facilities' => ['parking', 'food_court', 'vip_lounge', 'media_center'],
                'status' => 'active'
            ],
            [
                'name' => 'City Arena',
                'city' => 'Surabaya',
                'country' => 'Indonesia',
                'capacity' => 45000,
                'surface_type' => 'hybrid',
                'address' => 'Jl. Ahmad Yani, Surabaya',
                'phone' => '+62-31-567-8901',
                'email' => 'contact@cityarena.sby.id',
                'website' => 'https://cityarena.sby.id',
                'facilities' => ['parking', 'restaurant', 'training_ground'],
                'status' => 'active'
            ],
            [
                'name' => 'Community Ground',
                'city' => 'Bandung',
                'country' => 'Indonesia',
                'capacity' => 25000,
                'surface_type' => 'grass',
                'address' => 'Jl. Asia Afrika, Bandung',
                'phone' => '+62-22-123-4567',
                'email' => 'info@communityground.bdg.id',
                'website' => 'https://communityground.bdg.id',
                'facilities' => ['parking', 'cafe', 'kids_playground'],
                'status' => 'active'
            ],
            [
                'name' => 'University Field',
                'city' => 'Yogyakarta',
                'country' => 'Indonesia',
                'capacity' => 15000,
                'surface_type' => 'grass',
                'address' => 'Jl. Cendana, Yogyakarta',
                'phone' => '+62-274-567-8901',
                'email' => 'sports@universityfield.ac.id',
                'website' => 'https://universityfield.ac.id',
                'facilities' => ['parking', 'canteen', 'gym'],
                'status' => 'active'
            ],
            [
                'name' => 'Sports Complex',
                'city' => 'Medan',
                'country' => 'Indonesia',
                'capacity' => 35000,
                'surface_type' => 'artificial',
                'address' => 'Jl. Gatot Subroto, Medan',
                'phone' => '+62-61-234-5678',
                'email' => 'info@sportscomplex.medan.id',
                'website' => 'https://sportscomplex.medan.id',
                'facilities' => ['parking', 'swimming_pool', 'tennis_court'],
                'status' => 'active'
            ],
            [
                'name' => 'Municipal Stadium',
                'city' => 'Semarang',
                'country' => 'Indonesia',
                'capacity' => 30000,
                'surface_type' => 'grass',
                'address' => 'Jl. Pemuda, Semarang',
                'phone' => '+62-24-345-6789',
                'email' => 'contact@municipalstadium.smg.id',
                'website' => 'https://municipalstadium.smg.id',
                'facilities' => ['parking', 'food_stalls', 'fan_shop'],
                'status' => 'active'
            ]
        ];

        foreach ($venues as $venue) {
            Venue::create($venue);
        }
    }
}
