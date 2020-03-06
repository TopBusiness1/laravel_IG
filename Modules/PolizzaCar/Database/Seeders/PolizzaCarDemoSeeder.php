<?php

namespace Modules\PolizzaCar\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Platform\Core\Helper\SeederHelper;

/**
 * Class PolizzaCarDemoSeeder
 */
class PolizzaCarDemoSeeder extends SeederHelper
{


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('polizza_car')->truncate();

        $value = array(
            array(
                'buyer_id' => 3,
                'id' => 1,
                'company_name' => 'Company 1',
                'company_vat' => '111111111111',
                'company_email' => 'email@email.com',
                'company_phone' => '12321312',
                'company_address' => 'Via Toscana',
                'company_city' => 'Milano',
                'company_cap' => '09010',
                'company_provincia' => 'MI',
                'country_id' => '122',
                'company_activity' => 'Description of their activities',
                'referente_name' => 'Adrian',
                'referente_email' => 'ukagree@gmail.com',
                'referente_phone' => '32132132321',
                'contract_code' => 'NR-312219',
                'works_type_id' => '3',
                'works_type_details' => '2',
                'works_descr' => 'Free text here',
                'works_duration_dd' => '365',
                'works_duration_mm' => '12',
                'works_place' => 'MI',
                'primary_works_place' => 'Milano',
                'manteniance_coverage' => '24',
                'cvg_decennial_liability' => '1',
                'car_p1_limit_amount' => '1000',
                'car_p2_limit_amount' => '1000',
                'car_p3_limit_amount' => 1000,
                'status_id' => '2'
            ),
            array(
                'buyer_id' => 3,
                'id' => 2,
                'company_name' => 'Company 2',
                'company_vat' => '0000000000',
                'company_email' => 'email@email.com',
                'company_phone' => '12321312',
                'company_address' => 'Via Toscana',
                'company_city' => 'Milano',
                'company_cap' => '09010',
                'company_provincia' => 'MI',
                'country_id' => '122',
                'company_activity' => 'Description of their activities',
                'referente_name' => 'Adrian',
                'referente_email' => 'ukagree@gmail.com',
                'referente_phone' => '32132132321',
                'contract_code' => 'NR-312219',
                'works_type_id' => '3',
                'works_type_details' => '4',
                'works_descr' => 'Free text here',
                'works_duration_dd' => '365',
                'works_duration_mm' => '12',
                'works_place' => 'MI',
                'primary_works_place' => 'Milano',
                'manteniance_coverage' => '24',
                'cvg_decennial_liability' => '1',
                'car_p1_limit_amount' => '1000',
                'car_p2_limit_amount' => '1000',
                'car_p3_limit_amount' => 1000,
                'status_id' => '1'
            ),
            array(
                'buyer_id' => 3,
                'id' => 3,
                'company_name' => 'Company 3',
                'company_vat' => '0000000000',
                'company_email' => 'email@email.com',
                'company_phone' => '12321312',
                'company_address' => 'Via Toscana',
                'company_city' => 'Milano',
                'company_cap' => '09010',
                'company_provincia' => 'MI',
                'country_id' => '122',
                'company_activity' => 'Description of their activities',
                'referente_name' => 'Adrian',
                'referente_email' => 'ukagree@gmail.com',
                'referente_phone' => '32132132321',
                'contract_code' => 'NR-312219',
                'works_type_id' => '3',
                'works_type_details' => '5',
                'works_descr' => 'Free text here',
                'works_duration_dd' => '365',
                'works_duration_mm' => '12',
                'works_place' => 'MI',
                'primary_works_place' => 'Milano',
                'manteniance_coverage' => '24',
                'cvg_decennial_liability' => '1',
                'car_p1_limit_amount' => '1000',
                'car_p2_limit_amount' => '1000',
                'car_p3_limit_amount' => 1000,
                'status_id' => '4'
            ),
        );


        $final = [];

        foreach ($value as $complete) {
            $complete['date_request'] = Carbon::yesterday();
            $complete['created_at'] = Carbon::yesterday();

            $final[] = $complete;
        }

        DB::table('polizza_car')->insert($final);
    }
}
