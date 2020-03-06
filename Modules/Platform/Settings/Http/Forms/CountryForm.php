<?php

namespace Modules\Platform\Settings\Http\Forms;

use Kris\LaravelFormBuilder\Form;

/**
 * Class CountryForm
 * @package Modules\Platform\Settings\Http\Forms
 */
class CountryForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'label' => trans('settings::country.form.name'),
        ]);
        $this->add('cod_iso', 'text', [
            'label' => trans('settings::country.form.cod_iso'),
        ]);
        $this->add('cod_uic', 'text', [
            'label' => trans('settings::country.form.cod_uic'),
        ]);
        $this->add('is_active', 'checkbox', [
            'label' => trans('settings::country.form.is_active'),
        ]);

        $this->add('submit', 'submit', [
            'label' => trans('settings::language.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);
    }
}
