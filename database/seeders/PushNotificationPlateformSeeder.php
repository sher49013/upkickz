<?php

namespace Database\Seeders;

use App\Models\PushNotificationPlatform;
use Illuminate\Database\Seeder;

class PushNotificationPlateformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        PushNotificationPlatform::updateOrCreate(['name' => 'android'], [
            'name' => 'android',
            'description' => 'Android push notification platform',
        ]);

        PushNotificationPlatform::updateOrCreate(['name' => 'ios'], [
            'name' => 'ios',
            'description' => 'IOS push notification platform',
        ]);

    }
}
