<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Validator;
use Auth;

class AuthController extends Controller {

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
	protected $loginPath="/login";

	use AuthenticatesAndRegistersUsers;

	/**
	 * Create a new authentication controller instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Guard  $auth
	 * @param  \Illuminate\Contracts\Auth\Registrar  $registrar
	 * @return void
	 */
	public function __construct(Guard $auth, Registrar $registrar)
	{
		$this->auth = $auth;
		$this->registrar = $registrar;

		$this->middleware('guest', ['except' => 'getLogout']);
	}


	/**
	 * AUTHENTICATE
	 *
	*/
	public function authenticate(Request $req)
	{
		# code...
		$validation_rules=[
			'username'=>'required|alpha_dash',
			'password'=>'required|min:6'
		];
		$validation_error_messages=[
			'username.required'=>'من فضلك أدخل أسم المستخدم',
			'username.alpha_dash'=>'أسم المستخدم يجب أن يكون حروف و أرقام و - و _ فقط',
			'password.required'=>'من فضلك أدخل كلمة المرور',
			'password.min'=>'كلمة المرور يجب أن تتكون علي الأقل من 6 حروف أو أرقام'
		];

		$validator=Validator::make($req->all(),$validation_rules,$validation_error_messages);
		if($validator->fails()){
			return redirect()->back()->withErrors($validator)->withInput();
		}

		if(Auth::attempt(['username'=>$req->input('username'),'password'=>$req->input('password')],$req->input('remember'))){
			return redirect()->intended('dashboard');
		}else{
			return redirect()->back()->withErrors(array('message'=>'أسم المستخم أو كلمة المرور خاطئ'))->withInput();
		}
	}

	/**
	 * @return Logout
	 *
	*/
	public function getLogout()
	{
		# code...
		Auth::logout();
		return redirect()->route('getLogin');
	}
}
