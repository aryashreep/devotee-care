<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BhaktiSadan;

class BhaktiSadanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bhaktiSadans = [
            ['name' => 'Anekal'],
            ['name' => 'Byrati Bande'],
            ['name' => 'Cottonpet'],
            ['name' => 'Devanahalli'],
            ['name' => 'Gita Saar'],
            ['name' => 'HAL'],
            ['name' => 'Haralur'],
            ['name' => 'Hoskote'],
            ['name' => 'HSR layout'],
            ['name' => 'Hulimavu'],
            ['name' => 'ISKCON Seshadripuram'],
            ['name' => 'IVF (Iskcon Vaishnavi Forum)'],
            ['name' => 'IYF (Iskcon Youth Forum)'],
            ['name' => 'JP Nagar 3rd Phase'],
            ['name' => 'JP Nagar 8th Phase'],
            ['name' => 'KFS'],
            ['name' => 'Melkote'],
            ['name' => 'Mysore'],
            ['name' => 'Nagarbhavi'],
            ['name' => 'Nagasandra'],
            ['name' => 'Nelamangala'],
            ['name' => 'Online (Gita Gyan)'],
            ['name' => 'Prakash Nagar'],
            ['name' => 'RajaRajeswari Nagar'],
            ['name' => 'Sahakarnagar'],
            ['name' => 'Sarjapur'],
            ['name' => 'SKBC - Kudlu/ Electronic City'],
            ['name' => 'Tumkur'],
            ['name' => 'Vijayanagar'],
            ['name' => 'Yelahanka'],
        ];

        // Insert the data only if it doesn't already exist
        foreach ($bhaktiSadans as $sadan) {
            BhaktiSadan::firstOrCreate($sadan);
        }
    }
}
