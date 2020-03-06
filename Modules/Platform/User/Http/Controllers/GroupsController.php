<?php

namespace Modules\Platform\User\Http\Controllers;

use Modules\Platform\Core\Http\Controllers\SettingsCrudController;
use Modules\Platform\User\Datatables\GroupsDatatable;
use Modules\Platform\User\Http\Forms\GroupForm;
use Modules\Platform\User\Http\Requests\GroupCreateRequest;
use Modules\Platform\User\Http\Requests\GroupUpdateRequest;
use Modules\Platform\User\Repositories\GroupRepository;

class GroupsController extends SettingsCrudController
{
    protected $datatable = GroupsDatatable::class;
    protected $formClass = GroupForm::class;
    protected $storeRequest = GroupCreateRequest::class;
    protected $updateRequest = GroupUpdateRequest::class;
    protected $repository = GroupRepository::class;


    protected $showFields = [
        'details' => [
            'name' => ['type' => 'text'],
        ],
        'users' => [
            'users' => ['type' => 'manyToMany', 'relation' => 'users', 'column' => 'name',],
        ]
    ];

    protected $languageFile = 'user::groups';

    protected $routes = [
        'index' => 'settings.groups.index',
        'create' => 'settings.groups.create',
        'show' => 'settings.groups.show',
        'edit' => 'settings.groups.edit',
        'store' => 'settings.groups.store',
        'destroy' => 'settings.groups.destroy',
        'update' => 'settings.groups.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Store entity
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        $request = \App::make($this->storeRequest ?? Request::class);

        $entity = \App::make($this->repository)->create($request->all());

        if (is_null($request->get('users'))) {
            $entity->users()->sync([]);
        } else {
            $entity->users()->sync($request->get('users'));
        }

        flash(trans($this->languageFile . '.created'))->success();

        return redirect(route($this->routes['index']));
    }

    /**
     * Update entity
     * @param $identifier
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($identifier)
    {
        $request = \App::make($this->updateRequest ?? Request::class);

        $repository = \App::make($this->repository);


        $entity = $repository->find($identifier);


        if (empty($entity)) {
            flash(trans($this->languageFile . '.entity_not_found'))->success();

            return redirect(route($this->routes['index']));
        }

        $entity = \App::make($this->repository)->update($request->all(), $identifier);


        if (is_null($request->get('users'))) {
            $entity->users()->sync([]);
        } else {
            $entity->users()->sync($request->get('users'));
        }

        flash(trans($this->languageFile . '.updated'))->success();

        return redirect(route($this->routes['show'], $entity));
    }
}
