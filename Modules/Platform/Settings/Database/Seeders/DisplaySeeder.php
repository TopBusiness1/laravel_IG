<?php

namespace Modules\Platform\Settings\Database\Seeders;

use Illuminate\Database\Seeder;
use Krucas\Settings\Facades\Settings;
use Modules\Platform\Core\Helper\SettingsHelper;

/**
 * Class SettingsSeeder
 */
class DisplaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Settings::set(SettingsHelper::S_DISPLAY_SHOW_LOGO_ON_LOGIN, 1);
        Settings::set(SettingsHelper::S_DISPLAY_SHOW_LOGO_IN_APPLICATION, 1);
        Settings::set(SettingsHelper::S_DISPLAY_SHOW_LOGO_IN_PDF, 1);
        Settings::set(SettingsHelper::S_DISPLAY_APPLICATION_NAME, 'IG Platform');

        Settings::set(SettingsHelper::S_DISPLAY_SIDEBAR_BACKGROUND, 'blue.png');

        Settings::set(SettingsHelper::S_ANNOUNCEMENT_MESSAGE, '');
        Settings::set(SettingsHelper::S_ANNOUNCEMENT_DISPLAY_CLASS, '');

        Settings::set(SettingsHelper::S_DISPLAY_LOGO_UPLOAD, 'storage/files/logo/logo__1.png',1);
        Settings::set(SettingsHelper::S_COMPANY_NAME, 'IG');
        Settings::set(SettingsHelper::S_COMPANY_ADDRESS_, '86-90 Paul Street');
        Settings::set(SettingsHelper::S_COMPANY_CITY, 'London');
        Settings::set(SettingsHelper::S_COMPANY_STATE, '');
        Settings::set(SettingsHelper::S_COMPANY_POSTAL_CODE, 'EC2A');
        Settings::set(SettingsHelper::S_COMPANY_COUNTRY, 'United Kingdom');
        Settings::set(SettingsHelper::S_COMPANY_PHONE, 'xxx xxx');
        Settings::set(SettingsHelper::S_COMPANY_FAX, '');
        Settings::set(SettingsHelper::S_COMPANY_WEBSITE, 'http://avhsoftware.com');
        Settings::set(SettingsHelper::S_COMPANY_VAT_ID, '');

    }
}
