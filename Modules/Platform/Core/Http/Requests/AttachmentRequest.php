<?php

namespace Modules\Platform\Core\Http\Requests;

use App\Http\Requests\Request;

/**
 *
 * Class AttachmentRequest
 * @author Vaance <a.vacca@vaance.com>
 * @package Modules\Platform\Core\Http\Requests
 */
class AttachmentRequest extends Request
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
            'entityClass' => 'required',
            'entityId' => 'required',
            'files' => 'mimes:'.config('vaance.file_upload_laravel_validation')
        ];
    }
}
