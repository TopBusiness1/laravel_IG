<?php

namespace Modules\Platform\Settings\Database\Seeders;

use Illuminate\Support\Facades\DB;
use Modules\Platform\Companies\Entities\Company;
use Modules\Platform\Core\Helper\CrudHelper;
use Modules\Platform\Core\Helper\SeederHelper;
use Modules\Platform\User\Entities\Role;
use Modules\Platform\User\Repositories\RoleRepository;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

/**
 * Class SettingsSeeder
 */
class PermissionsSeeder extends SeederHelper
{

    private static $_DEFAULT_ROLES = array(
        ['id' => 1, 'display_name' => 'Admin', 'name' => 'admin', 'guard_name' => 'web'],
        ['id' => 2, 'display_name' => 'Supervisor', 'name' => 'supervisor', 'guard_name' => 'web'],
        ['id' => 3, 'display_name' => 'Buyer Supervisor', 'name' => 'buyer_supervisor', 'guard_name' => 'web'],
        ['id' => 4, 'display_name' => 'Buyer', 'name' => 'buyer', 'guard_name' => 'web'],
        ['id' => 5, 'display_name' => 'User', 'name' => 'user', 'guard_name' => 'web'],
    );

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->clear();

        $this->addAdminPermissions();

        $this->addSupervisorPermissions();

        $this->addBuyerSupervisorRolePermissions();

        $this->addBuyerRolePermissions();
        
        $this->addUserRolePermissions();

    }

    private function clear()
    {
        \Schema::disableForeignKeyConstraints();

        DB::table('roles')->truncate();
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_permissions')->truncate();
    }

    /**
     * Admin permissions
     */
    private function addAdminPermissions()
    {

        $roles = [['id' => '1', 'display_name' => 'Admin', 'name' => 'admin', 'guard_name' => 'web']];

        //Default Permission & Role seeder
        $roleRepo = \App::make(RoleRepository::class);

        // Synchronize permissions
        $result = $roleRepo->synchModulePermissions(true);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        DB::table('roles')->insert(CrudHelper::setDatesInArray($roles));

        $admin = Role::findById(1);

        $permissions = \Spatie\Permission\Models\Permission::all();

        if (count($permissions) == 0) {
            $roleRepo->synchModulePermissions();

            $permissions = Permission::all();
        }

        foreach ($permissions as $permission) {
            $admin->permissions()->attach($permission->id);
        }
    }

    /**
     * supervisor permissions
     */
    private function addSupervisorPermissions()
    {

        $roles = [['id' => '2', 'display_name' => 'Supervisor', 'name' => 'supervisor', 'guard_name' => 'web']];

        //Default Permission & Role seeder
        $roleRepo = \App::make(RoleRepository::class);

        // Synchronize permissions
        $result = $roleRepo->synchModulePermissions(true);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        DB::table('roles')->insert(CrudHelper::setDatesInArray($roles));

        $supervisor = Role::findById(2);

        $permissions = \Spatie\Permission\Models\Permission::all();

        if (count($permissions) == 0) {
            $roleRepo->synchModulePermissions();

            $permissions = Permission::all();
        }

        foreach ($permissions as $permission) {
            $supervisor->permissions()->attach($permission->id);
        }
    }

    /**
     * Buyer Supervisor Permissions
     */
    private function addBuyerSupervisorRolePermissions()
    {

        $roles = [['id' => '3', 'display_name' => 'Buyer Supervisor', 'name' => 'buyer_supervisor', 'guard_name' => 'web']];

        //Default Permission & Role seeder
        $roleRepo = \App::make(RoleRepository::class);

        // Synchronize permissions
        $result = $roleRepo->synchModulePermissions(true);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        DB::table('roles')->insert(CrudHelper::setDatesInArray($roles));

        $supervisor = Role::findById(3);

        $permissions = \Spatie\Permission\Models\Permission::all()
            ->where('name','!=','settings.access')
            ->where('name','!=','company.settings')
            ->where('name','!=','polizzacar.settings')
            ->where('name','!=','polizzacar.update')
            ->where('name', '!=', 'polizzacar.destroy');     

        if (count($permissions) == 0) {
            $roleRepo->synchModulePermissions();

            $permissions = Permission::all();
        }

        foreach ($permissions as $permission) {
            $supervisor->permissions()->attach($permission->id);
        }
        
    }

    /**
     * Buyer Permissions
     */
    private function addBuyerRolePermissions()
    {

        $roles = [['id' => '4', 'display_name' => 'Buyer', 'name' => 'buyer', 'guard_name' => 'web']];

        //Default Permission & Role seeder
        $roleRepo = \App::make(RoleRepository::class);

        // Synchronize permissions
        $result = $roleRepo->synchModulePermissions(true);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        DB::table('roles')->insert(CrudHelper::setDatesInArray($roles));

        $buyer = Role::findById(4);

        $permissions = \Spatie\Permission\Models\Permission::all()
            ->where('name','!=','settings.access')
            ->where('name','!=','company.settings')
            ->where('name','!=','polizzacar.settings')
            ->where('name','!=','polizzacar.update')
            ->where('name', '!=', 'polizzacar.destroy');
        
        if (count($permissions) == 0) {
            $roleRepo->synchModulePermissions();

            $permissions = Permission::all();
        }

        foreach ($permissions as $permission) {
            $buyer->permissions()->attach($permission->id);
        }
        
    }

    /**
     * User Permissions
     */
    private function addUserRolePermissions()
    {

        $roles = [['id' => '5', 'display_name' => 'User', 'name' => 'user', 'guard_name' => 'web']];

        //Default Permission & Role seeder
        $roleRepo = \App::make(RoleRepository::class);

        // Synchronize permissions
        $result = $roleRepo->synchModulePermissions(true);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        DB::table('roles')->insert(CrudHelper::setDatesInArray($roles));

        $user = Role::findById(5);

        
        $permissions = \Spatie\Permission\Models\Permission::all()
           ->where('name','!=','settings.access')
            ->where('name','!=','company.settings')
            ->where('name','!=','polizzacar.settings')
            ->where('name','!=','polizzacar.create')
            ->where('name','!=','polizzacar.update')
            ->where('name', '!=', 'polizzacar.destroy')
            ->where('name','!=','procurement.destroy')
            ->where('name','!=','procurement.browse')
            ->where('name','!=','procurement.create')
            ->where('name','!=','procurement.update');

        if (count($permissions) == 0) {
            $roleRepo->synchModulePermissions();

            $permissions = Permission::all();
        }

        foreach ($permissions as $permission) {
            $user->permissions()->attach($permission->id);
        }
        
    }

}
