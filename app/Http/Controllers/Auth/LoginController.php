<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Contract\Database;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth as LaravelAuth;
use Illuminate\Auth\GenericUser;

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

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Auth $auth,Database $database)
    {
        $this->database = $database;
        $this->auth = $auth;
    }

    public function handleCallback(Request $request){
        try {
            $userSession = LaravelAuth::user();
            $user = $request->json('user');
            
            
            $id = $user['id'];
            $name = $user['name'];
            $email = $user['email'];
            
            if (!$this->database->getReference('users/' . $id)->getSnapshot()->exists()) {
                $this->database->getReference('users/' . $id)->set([
                    'name' => $name,
                    'email' => $email,
                ]);
            }

            $request->session()->put('id', $id);
            $request->session()->put('name', $name);
            return response()->json(['redirect' => '/dashboard']);
        } catch (Exception $e) {   
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function logout(){
        Session::flush();
        return redirect('/');
    }
}
