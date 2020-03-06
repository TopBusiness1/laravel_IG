<?php

namespace Modules\Platform\Settings\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Platform\Core\Helper\CrudHelper;
use Modules\Platform\User\Entities\User;

/**
 * Class SettingsSeeder
 */
class UserSeeder extends Seeder
{
    private static $_USERS = [
        [
            'id' => 2,
            'email' => 'i.rossini@strategicagroup.com',
            'is_active' => 1,
            'first_name' => 'Igor',
            'last_name' => 'Rossini',
            'name' => 'Igor Rossini - Strategica',
            'access_to_all_entity' => 1,
            'theme' => 'theme-italgas',
            'role_id' => 2
        ],
        [
            'id' => 3,
            'email' => 'i.rossini@vaance.com',
            'is_active' => 1,
            'first_name' => 'Igor',
            'last_name' => 'Rossini',
            'name' => 'Igor Rossini - Buyer',
            'access_to_all_entity' => 1,
            'theme' => 'theme-italgas',
            'role_id' => 4
        ]
    ];

    private static $_GROUPS = [
        ['id' => 1, 'name' => 'Napoli'],
        ['id' => 2, 'name' => 'Torino'],
        ['id' => 3, 'name' => 'Milano']
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->clear();

        $this->addAdmin();        

        $this->addDefault();
    }

    private function clear()
    {
        DB::table('model_has_roles')->truncate();

        DB::table('users')->truncate();

        DB::table('group_user')->truncate();
        DB::table('groups')->truncate();
    }

    private function addAdmin()
    {
        $admin = [
            'id' => 1,
            'email' => 'adrianovacca@gmail.com',
            'is_active' => 1,
            'first_name' => 'Admin',
            'last_name' => 'Platform',
            'name' => 'Admin Platform',
            'access_to_all_entity' => 1,
            'theme' => 'theme-italgas',
            'role_id' => 1
        ];

        $admin['password'] = \Illuminate\Support\Facades\Hash::make('admin');;
        $admin['created_at'] = \Carbon\Carbon::now();
        $admin['updated_at'] = \Carbon\Carbon::now();

        DB::table('users')->insert($admin);

        User::find(1)->syncRoles(1);
    }

    private function addDefault()
    {
        foreach (self::$_USERS as $user) {
            $user['password'] = \Illuminate\Support\Facades\Hash::make('admin');
            $user['created_at'] = \Carbon\Carbon::now();
            $user['updated_at'] = \Carbon\Carbon::now();
            DB::table('users')->insert($user);
        }

        User::find(2)->syncRoles(2);
        User::find(3)->syncRoles(4);

        DB::table('groups')->insert(CrudHelper::setDatesInArray(self::$_GROUPS));
    }
}
