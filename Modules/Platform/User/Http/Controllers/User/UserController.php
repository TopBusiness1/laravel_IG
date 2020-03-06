<?php

namespace Modules\Platform\User\Http\Controllers\User;

use Intervention\Image\Facades\Image;
use Modules\Platform\Core\Helper\UserHelper;
use Modules\Platform\Core\Http\Controllers\SettingsCrudController;
use Modules\Platform\User\Datatables\UsersDatatable;
use Modules\Platform\User\Http\Forms\ChangePasswordForm;
use Modules\Platform\User\Http\Forms\UserForm;
use Modules\Platform\User\Http\Requests\UserCreateRequest;
use Modules\Platform\User\Http\Requests\UserRequest;
use Modules\Platform\User\Http\Requests\AccountUpdateRequest;
use Modules\Platform\User\Http\Requests\UserUpdateRequest;
use Modules\Platform\User\Repositories\UserRepository;

/**
 * CRUD UserController
 *
 * Class UserController
 * @package Modules\Platform\User\Http\Controllers\User
 */
class UserController extends SettingsCrudController
{

    protected $demoMode = true;

    protected $datatable = UsersDatatable::class;

    protected $formClass = UserForm::class;

    protected $storeRequest = UserCreateRequest::class;

    protected $updateRequest = UserUpdateRequest::class;

    protected $repository = UserRepository::class;

    protected $moduleName = 'user';

    protected $jsFiles = [
        'VAANCE_Users.js'
    ];

    protected $includeViews = [
        'user::users.changePassword'
    ];

    protected $languageFile = 'user::users';


    protected $showFields = [
        'login_and_role' => [
            'first_name' => ['type' => 'text',  'col-class' => 'col-lg-4'],
            'last_name' => ['type' => 'text',  'col-class' => 'col-lg-4'],
            'email' => ['type' => 'email',  'col-class' => 'col-lg-4'],

            'roles' => ['type' => 'oneToMany', 'relation' => 'roles', 'column' => 'display_name'],
        ],
        'address' => [
            'address_country' => ['type' => 'text'],
            'address_state' => ['type' => 'text'],
            'address_city' => ['type' => 'text'],
            'address_postal_code' => ['type' => 'text'],
            'address_street' => ['type' => 'text'],
        ],
        'more' => [
            'title' => ['type' => 'text'],
            'department' => ['type' => 'text'],
            'office_phone' => ['type' => 'text'],
            'mobile_phone' => ['type' => 'text'],
            'home_phone' => ['type' => 'text'],
            'signature' => ['type' => 'text'],
            'fax' => ['type' => 'text'],
            'secondary_email' => ['type' => 'email'],
        ],
        'settings' => [
            'theme' => ['type' => 'text'],
            'language_id' => ['type' => 'manyToOne', 'relation' => 'language', 'column' => 'name','dont_translate' => true],
            'date_format_id' => ['type' => 'manyToOne', 'relation' => 'dateFormat', 'column' => 'name','dont_translate' => true],
            'time_format_id' => ['type' => 'manyToOne', 'relation' => 'timeFormat', 'column' => 'name','dont_translate' => true],
            'time_zone' => ['type' => 'text']
        ],
        'profile' => [
            'profile_pic_conf' => ['type' => 'localized_text'],
            'profile_picture' => ['type' => 'none', 'hide_in_show' => true]

        ],
        'others' => [
            'created_at' => ['type'=>'datetime','hide_in_form'=>true],
            'updated_at' => ['type'=>'datetime','hide_in_form'=>true]
        ]
    ];


    protected $routes = [
        'index' => 'settings.users.index',
        'show' => 'settings.users.show',
        'create' => 'settings.users.create',
        'edit' => 'settings.users.edit',
        'update' => 'settings.users.update',
        'store' => 'settings.users.store',
        'destroy' => 'settings.users.destroy'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Overwritten function for add password form
     * @param $identifier
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function show($identifier)
    {
        $view = parent::show($identifier);

        $passwordForm = $this->form(ChangePasswordForm::class, [
            'method' => 'POST',
            'url' => route('settings.users.change-password', $identifier),
            'id' => 'user_change_password'
        ]);

        $view->with('passwordForm', $passwordForm);

        return $view;
    }

    /**
     * overwritten function to apply password
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        $request = \App::make($this->storeRequest ?? Request::class);

        $input = $request->all();

        $randomPassword = UserHelper::randomPassword(6);

        $input['password'] = bcrypt($randomPassword);

        $entity = \App::make($this->repository)->create($input);

        if (is_null($request->get('roles'))) {
            $entity->syncRoles([]);
        } else {
            $entity->syncRoles($request->get('roles'));
        }

        flash(trans($this->languageFile . '.created', ['password'=>$randomPassword]))->success();

        return redirect(route($this->routes['index']));
    }

    /**
     * Update Entity
     * overwritten function to apply syncRoles
     *
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


        if (is_null($request->get('roles'))) {
            $entity->syncRoles([]);
        } else {
            $entity->syncRoles($request->get('roles'));
        }

        // UPLOAD PROFILE PICTURE
        $profilePicture = $request->file('profile_picture');

        if ($profilePicture != null) {
            $image = 'profile_' . $identifier . '_.' . $profilePicture->getClientOriginalExtension();

            $uploadSuccess = $profilePicture->move(UserHelper::PROFILE_PICTURE_UPLOAD, $image);

            if ($uploadSuccess) {

                // Resize uploaded image to 100x100
                $img = Image::make(base_path() . '/public/' . UserHelper::PROFILE_PICTURE_UPLOAD . $image)->resize(
                    100,
                    100
                );
                $img->save(base_path() . '/public/' . UserHelper::PROFILE_PICTURE_UPLOAD . $image);

                $entity = \App::make($this->repository)->update([
                    'profile_image_path' => $image
                ], $identifier);
            }
        }

        flash(trans($this->languageFile . '.updated'))->success();

        return redirect(route($this->routes['show'], $entity));
    }

    protected function setupCustomButtons()
    {
        $this->customShowButtons[] = array(
            'attr' => [
                'class' => 'btn bg-pink waves-effect',
                'data-toggle' => 'modal',
                'data-target' => '#defaultModal'
            ],
            'label' => trans('user::users.change_password')
        );

        $this->customShowButtons[] = array(
            'attr' => [
                'class' => 'btn bg-pink waves-effect',
                'onclick' => 'VAANCE_Users.loginAsUser("' . $this->entity->id . '")',
            ],
            'label' => trans('user::users.ghost_login'),
        );

        $this->customShowButtons[] = array(
            'href' => route('settings.users.activity', $this->entity->id),
            'attr' => [
                'class' => 'btn bg-pink waves-effect',
            ],
            'label' => trans('user::users.activity_log')
        );
    }

    public function isAdmin()
    {
        return $this->roles()->where('roles.id', '=', 1)->count();
    }

    public function isStrategica()
    {
        return $this->roles()->where('roles.id', '=', 2)->count();
    }

    public function isBuyerSupervisor()
    {
        return $this->roles()->where('roles.id', '=', 3)->count();
    }
    public function isBuyer()
    {
        return $this->roles()->where('roles.id', '=', 4)->count();
    }

    public function isUser()
    {
        return $this->roles()->where('roles.id', '=', 5)->count();
    }
}
