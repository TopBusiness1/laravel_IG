<?php

namespace Modules\PolizzaCar\Service;

use Modules\PolizzaCar\Entities\PolizzaCar;
use Modules\PolizzaCar\Entities\PolizzaCarStatus;
use Modules\PolizzaCar\Entities\PolizzaCarProcurement;
use Carbon\Carbon;

/**
 * Class PolizzaCarService
 * @package Modules\PolizzaCar\Service
 */
class PolizzaCarService
{
    public function countByStatus($status)
    {
        // $polizze = PolizzaCar::query()
        //     ->select('polizza_car.*', 'polizza_car_status.name as status')
        //     ->leftJoin('polizza_car_status', 'polizza_car.status_id', '=', 'polizza_car_status.id')
            
        //     ->whereNull('polizza_car.deleted_at');

        // return $polizze->count();

        $arr_status = [
            1=>'status_1',
            2=>'status_2',
            3=>'status_3',
            4=>'status_4',
            5=>'status_5',
            6=>'status_6',
            7=>'status_7'
        ];

        $arr_procurements = [
            1=>'with_insurance_number',
            2=>'without_insurance_number'
        ];

        $arr_count = [];

        $user = auth()->user();

        foreach ($arr_status as $key => $value) {
            $polizza = PolizzaCar::query()
                ->select('status_id')
                ->where('status_id', $key)
                ->whereNull('deleted_at');

            switch ($user->role_id) {
                case '5': // User
                    $polizza = $polizza->where('company_email', $user->email);
                    break;

                case '4': // Buyer
                    $polizza = $polizza->where('group_id', $user->group_id);
                    break;
                
                default:
                    # code...
                    break;
            }
            $arr_count[$value] = $polizza->count();
        }

        foreach ($arr_procurements as $key => $value) {
            $procurement = PolizzaCarProcurement::query()
                ->select('id')
                ->whereNull('deleted_at');

            if ($value == 'with_insurance_number') {
                $procurement = $procurement->whereNotNull('insurance_policy');
            } else if ($value == 'without_insurance_number') {
                $procurement = $procurement->whereNull('insurance_policy');
            }

            $arr_count[$value] = $procurement->count();
        }
        
        return $arr_count;
    }

    /**
     * Create Polizza from Appalto
     *
     * @param $procurementId
     * @return PolizzaCar
     */
    public function convertToRequest($procurementId){

        $procurement = PolizzaCarProcurement::findOrFail($procurementId);

        $polizza = new PolizzaCar();

        $polizza->fill($procurement->toArray());

        $polizza->date_request = Carbon::now();
        $polizza->status_id = 1;
        $polizza->procurement_id = $procurement->id;
        $polizza->company_name = $procurement->company_name;
        $polizza->company_vat = $procurement->company_vat;
        $polizza->company_email = $procurement->company_email;
        $polizza->company_phone = $procurement->company_phone;
        $polizza->company_address = $procurement->company_address;
        $polizza->company_city = $procurement->company_city;
        $polizza->company_cap = $procurement->company_cap;
        $polizza->company_provincia = $procurement->company_provincia;
        $polizza->country_id = $procurement->country_id;
        $polizza->works_type_details = $procurement->works_type_details;
        $polizza->works_descr = $procurement->works_descr;
        

        /* if($procurement->owner != null ) {
            $polizza->changeOwnerTo($procurement->owner);
        } */
        $polizza->save();

        /* foreach ($procurement->procurementEmails as $procurementEmail){
            $contactEmail = new ContactEmail();
            $contactEmail->email = $procurementEmail->email;
            $contactEmail->is_default = $procurementEmail->is_default;
            $contactEmail->is_active = $procurementEmail->is_active;
            $contactEmail->is_marketing = $procurementEmail->is_marketing;
            $contactEmail->notes = $procurementEmail->notes;
            $contactEmail->contact()->associate($contact);
            $contactEmail->save();
        } */

        return $polizza;

    }

    

}
