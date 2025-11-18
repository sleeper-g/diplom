<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::setValue('regular_price', config('cinema.default_regular_price'));
        Setting::setValue('vip_price', config('cinema.default_vip_price'));
    }
}


