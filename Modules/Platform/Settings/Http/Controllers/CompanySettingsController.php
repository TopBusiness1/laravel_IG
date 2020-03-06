<?php

namespace Modules\Platform\Settings\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Krucas\Settings\Facades\Settings;
use Modules\Platform\Core\Helper\SettingsHelper;
use Modules\Platform\Core\Http\Controllers\AppBaseController;
use Modules\Platform\Settings\Http\Forms\CompanySettingsForm;
use Modules\Platform\Settings\Http\Requests\SaveCompanySettingsRequest;

/**
 *
 * Update Company Settings
 *
 * Class CompanySettingsController
 * @package Modules\Platform\Settings\Http\Controllers
 */
class CompanySettingsController extends AppBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    use FormBuilderTrait;

    /**
     * Show form and load values
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $view = view('settings::company_settings.company_settings');

        $companySettingsForm = $this->form(CompanySettingsForm::class, [
            'method' => 'POST',
            'url' => route('settings.company_settings'),
            'id' => 'company_settings_form',
            'model' => [
                'company_name' => Settings::get(SettingsHelper::S_COMPANY_NAME),
                'address' => Settings::get(SettingsHelper::S_COMPANY_ADDRESS_),
                'city' => Settings::get(SettingsHelper::S_COMPANY_CITY),
                'state' => Settings::get(SettingsHelper::S_COMPANY_STATE),
                'postal_code' => Settings::get(SettingsHelper::S_COMPANY_POSTAL_CODE),
                'country' => Settings::get(SettingsHelper::S_COMPANY_COUNTRY),
                'phone' => Settings::get(SettingsHelper::S_COMPANY_PHONE),
                'fax' => Settings::get(SettingsHelper::S_COMPANY_FAX),
                'website' => Settings::get(SettingsHelper::S_COMPANY_WEBSITE),
                'vat_id' => Settings::get(SettingsHelper::S_COMPANY_VAT_ID)
            ]
        ]);

        $view->with('company_settings_form', $companySettingsForm);

        return $view;
    }

    /**
     * Upda
     * @param SaveCompanySettingsRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(SaveCompanySettingsRequest $request)
    {
        $form = $this->form(CompanySettingsForm::class);

        // Update Settings In Database
        Settings::set(SettingsHelper::S_COMPANY_NAME, $form->getField('company_name')->getRawValue());
        Settings::set(SettingsHelper::S_COMPANY_ADDRESS_, $form->getField('address')->getRawValue());
        Settings::set(SettingsHelper::S_COMPANY_CITY, $form->getField('city')->getRawValue());
        Settings::set(SettingsHelper::S_COMPANY_STATE, $form->getField('state')->getRawValue());
        Settings::set(SettingsHelper::S_COMPANY_POSTAL_CODE, $form->getField('postal_code')->getRawValue());
        Settings::set(SettingsHelper::S_COMPANY_COUNTRY, $form->getField('country')->getRawValue());
        Settings::set(SettingsHelper::S_COMPANY_PHONE, $form->getField('phone')->getRawValue());
        Settings::set(SettingsHelper::S_COMPANY_FAX, $form->getField('fax')->getRawValue());
        Settings::set(SettingsHelper::S_COMPANY_WEBSITE, $form->getField('website')->getRawValue());
        Settings::set(SettingsHelper::S_COMPANY_VAT_ID, $form->getField('vat_id')->getRawValue());

        flash(trans('settings::company_settings.settings_updated'))->success();

        return redirect(route('settings.company_settings'));
    }
}
