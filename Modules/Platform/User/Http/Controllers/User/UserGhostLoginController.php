<?php

namespace Modules\Platform\User\Http\Controllers\User;

use Modules\Platform\Core\Http\Controllers\AppBaseController;
use Modules\Platform\User\Repositories\UserRepository;

/**
 * Ghost login as selected user
 * Class UserGhostLoginController
 * @package Modules\Platform\User\Http\Controllers
 */
class UserGhostLoginController extends AppBaseController
{

    /**
     * @var UserRepository
     */
    private $userRepo;

    /**
     * UserGhostLoginController constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        parent::__construct();
        $this->userRepo = $repository;
    }

    /**
     * Ghost login as user
     *
     * @param $identifer
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function login($identifer)
    {
        $user = $this->userRepo->findWithoutFail($identifer);

        if (empty($user)) {
            flash(trans('user::users.user_not_found'))->error();

            return redirect()->back();
        }

        \Session::put('original_user', \Auth::user()->id);

        \Auth::login($user);

        flash(trans('user::users.logged_as', ['full_name' => $user->name]))->warning();

        return redirect('/');
    }
}
