<?php

use Illuminate\Database\Seeder;
use App\Subscription;

class SubscriptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Subscription::create([
            'name' => 'Basic',
            'description' => 'This is the basic subscription plan with discount available depending on the number of months',
            'amount' => getenv('BASIC_AMOUNT')*100,  //BASIC_AMOUNT In Naira
            'duration' => 1 //Number of months
        ]);
    }
}
