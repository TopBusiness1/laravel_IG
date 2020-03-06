<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;
use Modules\Platform\User\Repositories\UserRepository;
use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';


    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository)
    {
        //parent::__construct();
        $this->middleware('guest');
        $this->userRepository = $userRepository;

    }

    /**
     * Registration form
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        if (!config('vaance.allow_registration')) {

            return response()->redirectTo('/login');

        }
        return view('auth.register');
    }
    
    /**
     *
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    protected function create(array $data)
    {
        return $this->userRepository->create([
            'first_name' => $data['first_name'],
            'name' => $data['first_name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        if(config('vaance.GOOGLE_RECAPTCHA_KEY')){
            $this->validate(request(),[
                'g-recaptcha-response' => 'required|recaptcha'
            ]);
        }

        return Validator::make($data, [
            'first_name' => 'required|string|max:255',
            'email' => [
                'max:255',
                'required',
                'email',
                Rule::unique('lead_email')->where(function ($query) {
                    $query->whereNull('deleted_at');
                })
            ],
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

}


