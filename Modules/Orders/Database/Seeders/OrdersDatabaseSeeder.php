<?php

namespace Modules\Orders\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Platform\Core\Helper\SeederHelper;
use Carbon\Carbon;

class OrdersDatabaseSeeder extends SeederHelper
{


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('orders_dict_status')->truncate();

        $value = array(
            array('id' => 1,'name' => 'Created','icon'=>'fa fa-plus-circle','color'=>'col-orange','system_name'=>'created'),
            array('id' => 2,'name' => 'Approved','icon'=>'fa fa-check-circle-o','color'=>'col-green','system_name'=>'approved'),
            array('id' => 3,'name' => 'Delivered','icon'=>'fa fa-truck','color'=>'col-blue','system_name'=>'delivered'),
            array('id' => 4,'name' => 'Cancelled','icon'=>'fa fa-ban','color'=>'col-grey','system_name'=>'cancelled')
        );

        $final = [];

        foreach ($value as $complete) {
            $complete['created_at'] = Carbon::now();

            $final[] = $complete;
        }

        DB::table('orders_dict_status')->insert($final);
    }

}
