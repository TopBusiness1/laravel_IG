<?php

namespace Modules\PolizzaCar\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Platform\Core\Helper\SeederHelper;

/**
 * Class TariffeSeeder
 */
class TariffeSeeder extends SeederHelper
{


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('polizza_car_piano_tariffario')->truncate();

        $value = array(
            array(
                'id' => 1,
                'name' => '1° rischio RCT',
                'mm_24' => '1.528',
                'mm_36' => '1.65',
                'tax_rate' => '22.25',
                'commission' => '17'
            ),
            array(
                'id' => 2,
                'name' => '2° rischio RCT',
                'mm_24' => '1.284',
                'mm_36' => '1.467',
                'tax_rate' => '22.25',
                'commission' => '17'
            ),
        );

        $final = [];

        foreach ($value as $complete) {
            $complete['created_at'] = Carbon::now();

            $final[] = $complete;
        }

        DB::table('polizza_car_piano_tariffario')->insert($final);
    }
}
