<?php

namespace Modules\Platform\User\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class RoleUpdateRequest
 * @package Modules\Platform\User\Http\Requests
 */
class RoleUpdateRequest extends Request
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
            'display_name' => 'max:255|alpha_dash|required|unique:roles,id,' . $this->get('id'),
            'name' => 'max:255|alpha|required|unique:roles,id,' . $this->get('id'),
            'guard_name' => 'alpha'
        ];
    }
}
