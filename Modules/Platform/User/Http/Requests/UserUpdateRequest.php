<?php

namespace Modules\Platform\User\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class UserUpdateRequest
 * @package Modules\Platform\User\Http\Requests
 */
class UserUpdateRequest extends Request
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
            'first_name' => 'required|max:255',
            'email' => 'max:255|required|unique:users,id,' . $this->get('id'),
            'language_id'=>'required',
            'date_format_id'=>'required',
            'time_format_id'=>'required',
            'time_zone'=>'required',
            'theme' => 'required',
            'profile_picture' => 'mimes:jpeg,png'
        ];
    }
}
