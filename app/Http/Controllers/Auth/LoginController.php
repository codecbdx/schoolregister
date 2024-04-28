<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function attemptLogin(Request $request)
    {
        // Intenta autenticar al usuario
        $credentials = $this->credentials($request);
        $user = $this->guard()->getProvider()->retrieveByCredentials($credentials);

        // Verifica si el usuario no es encontrado
        if (!$user) {
            throw ValidationException::withMessages([
                $this->username() => [trans('auth.failed')],
            ]);
        }

        // Comprueba si el usuario está cancelado o suspendido
        if ($user->cancelled == 1) {

            throw ValidationException::withMessages([
                $this->username() => [trans('auth.cancelled')],
            ]);
        } elseif ($user->cancelled == 2) {

            throw ValidationException::withMessages([
                $this->username() => [trans('auth.suspended')],
            ]);
        } else {
            // Si el usuario es encontrado, pero la contraseña no coincide
            if (!$this->guard()->getProvider()->validateCredentials($user, $credentials)) {
                throw ValidationException::withMessages([
                    'password' => [trans('auth.password')],
                ]);
            }

            return $this->guard()->attempt(
                $credentials, $request->filled('remember')
            );
        }
    }
}
