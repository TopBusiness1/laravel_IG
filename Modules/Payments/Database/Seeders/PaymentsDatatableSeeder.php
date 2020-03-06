<?php

namespace Modules\Payments\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Platform\Core\Helper\SeederHelper;
use Carbon\Carbon;

class PaymentsDatatableSeeder extends SeederHelper
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('payments_dict_status')->truncate();

        $value = array(
            array('id' => 1,'name' => 'Submitted','icon'=>'fa fa-plus-circle','color'=>'col-orange'),
            array('id' => 2,'name' => 'Approved','icon'=>'fa fa-check-circle-o','color'=>'col-green'),
            array('id' => 3,'name' => 'Declined','icon'=>'fa fa-truck','color'=>'col-blue')
        );

        $final = [];

        foreach ($value as $complete) {
            $complete['created_at'] = Carbon::now();

            $final[] = $complete;
        }

        DB::table('payments_dict_status')->insert($final);
    }
    
   /*  public function dictionary($companyId)
    {

    

        $categoryData = [
            ['name' => 'Gas', 'company_id' => $companyId],
            ['name' => 'Travel', 'company_id' => $companyId],
            ['name' => 'Meals', 'company_id' => $companyId],
            ['name' => 'Car rental', 'company_id' => $companyId],
            ['name' => 'Cell phone', 'company_id' => $companyId],
            ['name' => 'Groceries', 'company_id' => $companyId],
            ['name' => 'Invoice', 'company_id' => $companyId],
        ];

        $this->saveOrUpdate('payments_dict_category', $categoryData);

        $paymentMethodData = [
            ['name' => 'Cash', 'company_id' => $companyId],
            ['name' => 'Cheque', 'company_id' => $companyId],
            ['name' => 'Credit card', 'company_id' => $companyId],
            ['name' => 'Direct debit', 'company_id' => $companyId],
        ];

        $this->saveOrUpdate('payments_dict_payment_method', $paymentMethodData);
    }
 */ 
    
}
