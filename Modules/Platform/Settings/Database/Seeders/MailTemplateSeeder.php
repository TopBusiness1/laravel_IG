<?php

namespace Modules\Platform\Settings\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Platform\Core\Helper\SeederHelper;
use Carbon\Carbon;

/**
 * Class MailTemplateSeeder
 * @package Modules\Platform\Settings\Database\Seeders
 */
class MailTemplateSeeder extends SeederHelper
{

    private static $MailTemplate = [
        [
            'name' => 'Welcome Mail', 
            'subject' => "Welcome to the family!",
            'message' => '<p>Hi {{first_name}} <br><br>Thanks for signing up ...., the advanced multicrm.</p><p><br></p><p>Here you can start and learn more information about crm -&nbsp;<a href="https://vaance.com/doc/laravel-vaance-crm/#1344">https://vaance.com/doc/laravel-vaance-crm/#1344</a>&nbsp;</p><p><br></p><p>Let me know if you have any questions, feedback or ideas -- just reply to this email!</p><p><br></p><p><br></p>',
        ]
    ];
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('vaance_email_template')->truncate();

        $final = [];

        foreach (self::$MailTemplate as $languag) {
            $languag['created_at'] = Carbon::now();
            $languag['updated_at'] = Carbon::now();
            
            $final[] = $languag;
        }

        DB::table('vaance_email_template')->insert($final);

    }
}
