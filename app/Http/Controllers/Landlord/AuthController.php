&lt;?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    use AuthenticatesUsers, RegistersUsers, ResetsPasswords, VerifiesEmails;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest:landlord')->except('logout');
    }

    protected function guard()
    {
        return Auth::guard('landlord');
    }

    protected function broker()
    {
        return Password::broker('landlords');
    }

    public function showLoginForm()
    {
        return view('landlord.auth.login');
    }

    public function showRegistrationForm()
    {
        return view('landlord.auth.register');
    }

    public function showLinkRequestForm()
    {
        return view('landlord.auth.passwords.email');
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('landlord.auth.passwords.reset')->with(['token' => $token, 'email' => $request->email]);
    }

    public function showVerificationNotice()
    {
        return view('landlord.auth.verify');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:landlords'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        return Auth::guard('landlord')->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
