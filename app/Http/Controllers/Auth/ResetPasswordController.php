<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Modules\Platform\User\Entities\User;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    //use ResetsPasswords;
    use ResetsPasswords {
        reset as traitreset;
    }

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    //protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // parent::__construct();
        $this->middleware('guest');
    }

    public function showResetForm(Request $request, $token = null, $email)
    {
        // $user = User::where('verification_token', $token)->firstOrFail();
        $user = User::where('email', $email)->firstOrFail();
        // return view('auth.passwords.set', compact('user'));
        return view('auth.passwords.reset', compact('user'))
                    ->with('token', $token);
    }

    public function reset(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if ($validator->fails()) {
            //return view('index')->with('signflag', 'signup')->withErrors($validator);
            return back()->withErrors($validator)->withInput();
        }

        // $user = User::where('verification_token', $request->verification_token)->firstOrFail();
        $user = User::where('email', $request->email)->firstOrFail();

        $user->password = bcrypt($request->password);
        $user->verification_token = '';
        $user->save();
        //}

        \Auth::login($user);
        
        return redirect()->to('/login');
    }

}
