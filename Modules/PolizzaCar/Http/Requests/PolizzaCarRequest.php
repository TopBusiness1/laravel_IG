<?php

namespace Modules\PolizzaCar\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class PolizzaCarRequest
 * @package Modules\PolizzaCar\Http\Requests
 */
class PolizzaCarRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'date_request' => 'required',
            'company_name' => 'sometimes|required|min:3|nullable',
            'company_vat' => 'sometimes|required|min:11|nullable',
            'company_email' => 'sometimes|nullable|email',
            
            'company_phone' => 'sometimes|required|min:3|nullable',
                'company_address' => 'sometimes|required|min:3|nullable',
                'company_city' => 'sometimes|required|min:3|nullable',
                'company_cap' => 'sometimes|required|min:5|nullable',
                'company_provincia' => 'sometimes|required|min:2|nullable',

                'country_id' => 'sometimes|required|nullable',
                'company_activity' => 'sometimes|required|nullable',
                'referente_name' => 'sometimes|required|nullable',
                'referente_email' => 'sometimes|required|nullable',
                'referente_phone' => 'sometimes|required|nullable',
                'contract_code' => 'sometimes|required|nullable',
                'works_type_id' => 'sometimes|required|nullable',
                'works_type_details' => 'sometimes|required|nullable',
                'works_descr'=> 'sometimes|required|nullable|max:500',
                'works_duration_dd' => 'sometimes|required|nullable',
                'works_duration_mm' => 'sometimes|required|nullable',
                'works_place' => 'sometimes|required|nullable',
                'primary_works_place' => 'sometimes|required|nullable',
                'manteniance_coverage' => 'sometimes|required|nullable',
                'cvg_decennial_liability' => 'sometimes|required|nullable',
                'car_p1_limit_amount' => 'required|nullable|max:5000000',
                'car_p2_limit_amount' => 'required|nullable|max:5000000',
                'car_p3_limit_amount'=> 'required|nullable|max:5000000',
                'risk_id' => 'sometimes|required|nullable',
                'coeff_tariffa' => 'sometimes|required|nullable',
                'tax_rate' => 'sometimes|required|nullable',
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'company_name.required' => 'Ragione Sociale obbligatorio',
            'company_vat.required' => 'Partita IVA obbligatoria',
            'company_vat.min' => 'Partita IVA non valida'
        ];
    }
}
