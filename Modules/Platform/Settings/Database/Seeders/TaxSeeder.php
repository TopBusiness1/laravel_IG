<?php

namespace Modules\Platform\Settings\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Platform\Core\Helper\SeederHelper;

/**
 * Class SettingsTimeFormatSeeder
 */
class TaxSeeder extends SeederHelper
{

    private static $taxes = [
            ['name' => '23%', 'tax_value' => 0.23],
            ['name' => '8%', 'tax_value' => 0.08],
            ['name' => '5%', 'tax_value' => 0.05]
        ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('vaance_tax')->truncate();
        $final = [];

        foreach (self::$taxes as $tax) {
            $tax['created_at'] = Carbon::now();

            $final[] = $tax;
        }

        DB::table('vaance_tax')->insert($final);

        $this->saveOrUpdate('vaance_tax', self::$taxes);

    }
}
