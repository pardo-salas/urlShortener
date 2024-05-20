<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;
use Cookie;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Contract\Database;

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

            Cookie::queue('id',$id,60);
            Cookie::queue('name',$name,60);

            return response()->json(['redirect' => '/dashboard']);
        } catch (Exception $e) {   
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function showLoginForm(){
        return view('auth.login');
    }

    public function logout(){
        Cookie::queue(Cookie::forget('id'));
        return redirect('/');
    }
}
