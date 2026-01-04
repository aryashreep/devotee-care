<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Seva;

class SevaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sevas = [
            ['name' => 'Deity Services (Pujari, Dressing, Garland Making)'],
            ['name' => 'Prasadam Cooking & Preparation'],
            ['name' => 'Prasadam Distribution'],
            ['name' => 'Kirtan & Music'],
            ['name' => 'Preaching (Classes, Outreach)'],
            ['name' => 'Book Distribution'],
            ['name' => 'Festival Coordination'],
            ['name' => 'Yatra (Pilgrimage) Coordination'],
            ['name' => 'Kids & Children Programs'],
            ['name' => 'Youth Boys Programs (IYF)'],
            ['name' => 'Vaishnavi Girls Programs (IVF)'],
            ['name' => 'Donor Care & Fundraising'],
            ['name' => 'Temple Cleaning & Maintenance'],
            ['name' => 'Guest Reception & Hospitality'],
            ['name' => 'Technical Support (AV, IT)'],
            ['name' => 'Other'],
        ];

        foreach ($sevas as $seva) {
            Seva::firstOrCreate($seva);
        }
    }
}
