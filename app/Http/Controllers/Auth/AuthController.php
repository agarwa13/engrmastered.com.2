<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserCreated;
use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Socialite;
use Auth;


class AuthController extends Controller
{

    protected $redirectPath = '/';

    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;


    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     * New Users are Students by default (role_id = 1)
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role_id' => 1
        ]);
    }

    public function authenticated($request, $user){
        // If the request is from middleware then we want to return the user instead of redirecting
        if($request->has('register_user')){
            return $user;
        }else{
            return redirect()->intended($this->redirectPath());
        }
    }

    public function postRegister(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        Auth::login($this->create($request->all()));

        if(Auth::user()){
            event(new UserCreated(Auth::user()));
        }


        return redirect()->intended($this->redirectPath());
    }


    public function facebookLogin(Request $request){
        /*
         * Request Authorization from Google
         */
        if(!$request->has('code')){
            return $this->redirectToProvider('facebook');
        }

        /*
         * Google Callback. Authenticate User (Create if Doesn't Exist)
         */
        return $this->handleProviderCallback('facebook');
    }


    public function googleLogin(Request $request){
        /*
         * Request Authorization from Google
         */
        if(!$request->has('code')){
            return $this->redirectToProvider('google');
        }

        /*
         * Google Callback. Authenticate User (Create if Doesn't Exist)
         */
        return $this->handleProviderCallback('google');
    }


    /**
     * Redirect the user to the Provider authentication page.
     *
     * @return Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback($provider)
    {
        if($provider == 'github'){
            $socialUser = Socialite::driver($provider)->scopes(['user:email'])->user();
        }else{
            $socialUser = Socialite::driver($provider)->user();
        }


        if(!isset($socialUser->email) || $socialUser->email == null){
            return redirect('auth/login')->with('');
        }

        /*
         * Retrieve an Existing User with the same email Id or Create a new one
         */
        $user = User::firstOrCreate([
            'email' => $socialUser->email
        ]);

        /*
         * Update the user's Name
         */
        $user->name = $socialUser->name;

        /*
         * Update the Role Id if not already set
         */
        if(!$user->role_id){
            $user->role_id = 1;
        }

        $user->save();

        /*
         * Authenticate the User
         */
        Auth::login($user);

        /*
         * Redirect to Intended Destination or Home
         */
        return redirect()->intended('/');
    }


}
