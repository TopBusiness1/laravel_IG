<?php

use Illuminate\Database\Seeder;
use \Krucas\Settings\Facades\Settings;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // \Spatie\Activitylog\Models\Activity::truncate();
        // \Bnb\Laravel\Attachments\Attachment::truncate();

        $this->call( \Modules\PolizzaCar\Database\Seeders\PolizzaCarStatusSeeder::class);
        $this->call( \Modules\PolizzaCar\Database\Seeders\PolizzaCarWorksTypeSeeder::class);

        $this->call( \Modules\PolizzaCar\Database\Seeders\PolizzaCarDemoSeeder::class);

        $this->call( \Modules\PolizzaCar\Database\Seeders\PolizzaCarProcurementSeeder::class);

        $this->call( \Modules\PolizzaCar\Database\Seeders\TariffeSeeder::class);

        $this->call( \Modules\Orders\Database\Seeders\OrdersDemoSeederTableSeeder::class);

        $this->call( \Modules\Orders\Database\Seeders\OrdersDatabaseSeeder::class);

        $this->call( \Modules\Payments\Database\Seeders\PaymentsDatatableSeeder::class);

        
        
        // $this->call( \Modules\Platform\Notifications\Database\Seeders\NotificationsDemoSeeder::class);
/*
        $this->call(\Modules\Payments\Database\Seeders\PaymentDemoSeederTableSeeder::class);
        $this->call(\Modules\Campaigns\Database\Seeders\CampaignDemoSeederTableSeeder::class);
        $this->call(\Modules\Leads\Database\Seeders\LeadDemoSeederTableSeeder::class);
      

        Settings::set('s_subscription_paypal',true);
        Settings::set('s_subscription_stripe',true);
        Settings::set('s_subscription_cash',true);
        Settings::set('s_subscription_invoice_from','hello@vaance.com');
        */
    }
}
