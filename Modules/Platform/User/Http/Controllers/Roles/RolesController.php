<?php

namespace Modules\Platform\User\Http\Controllers\Roles;

use Modules\Platform\Core\Http\Controllers\SettingsCrudController;
use Modules\Platform\User\Datatables\RolesDatatable;
use Modules\Platform\User\Http\Forms\RoleForm;
use Modules\Platform\User\Http\Requests\RoleCreateRequest;
use Modules\Platform\User\Http\Requests\RoleUpdateRequest;
use Modules\Platform\User\Repositories\RoleRepository;

class RolesController extends SettingsCrudController
{
    protected $demoMode = true;

    protected $datatable = RolesDatatable::class;
    protected $formClass = RoleForm::class;
    protected $storeRequest = RoleCreateRequest::class;
    protected $updateRequest = RoleUpdateRequest::class;
    protected $repository = RoleRepository::class;

    protected $moduleName = 'user';

    protected $showFields = [
        'details' => [
            'display_name' => ['type' => 'text'],
            'name' => ['type' => 'text'],
            'guard_name' => ['type' => 'text'],
        ]
    ];

    protected $jsFiles = [
        'VAANCE_Roles.js'
    ];

    protected function setupCustomButtons()
    {
        $this->customShowButtons[] = array(
            'href' => route('settings.roles.permissions', $this->entity->id),
            'attr' => [
                'class' => 'btn bg-pink waves-effect',
            ],
            'label' => trans('user::roles.setup_permissions'),
        );
    }

    protected $languageFile = 'user::roles';

    protected $routes = [
        'index' => 'settings.roles.index',
        'create' => 'settings.roles.create',
        'show' => 'settings.roles.show',
        'edit' => 'settings.roles.edit',
        'store' => 'settings.roles.store',
        'destroy' => 'settings.roles.destroy',
        'update' => 'settings.roles.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $datatable = \App::make($this->datatable);

        $indexView = $this->views['index'];

        return $datatable->render($indexView);
    }
}
