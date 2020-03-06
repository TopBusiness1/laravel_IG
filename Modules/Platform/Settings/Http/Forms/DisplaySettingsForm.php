<?php

namespace Modules\Platform\Settings\Http\Forms;

use Kris\LaravelFormBuilder\Form;
use Modules\Platform\Core\Helper\FileHelper;

/**
 * Class DisplaySettingsForm
 * @package Modules\Platform\Settings\Http\Forms
 */
class DisplaySettingsForm extends Form
{
    public function buildForm()
    {
        $this->add('logo_separator', 'static', [
            'label_show' => false,
            'tag' => 'h2',
            'attr' => ['class' => 'card-inside-title'],
            'value' => trans('settings::display.logo_settings')
        ]);

        $this->add('s_display_logo_upload', 'file', [
            'label' => trans('settings::display.logo'),
        ]);

        $this->add('s_display_show_logo_in_application', 'switch', [
            'label' => trans('settings::display.show_logo_in_application'),
            'color' => 'switch-col-red'
        ]);

        $this->add('separator', 'static', [
            'label_show' => false,
            'tag' => 'h2',
            'attr' => ['class' => 'card-inside-title'],
            'value' => trans('settings::display.application_display_settings')
        ]);

        $this->add('s_display_application_name', 'text', [
            'label' => trans('settings::display.application_name'),
        ]);

        $this->add('submit', 'submit', [
            'label' => trans('settings::display.update_settings'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']

        ]);
    }
}
