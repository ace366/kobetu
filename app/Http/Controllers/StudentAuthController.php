<?php
// app/Http/Controllers/StudentAuthController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('students.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::guard('student')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/student/home')
                             ->with('status', 'ログインしました');
        }

        return back()->withErrors([
            'email' => 'メールアドレスまたはパスワードが正しくありません。',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('student.login')->with('status', 'ログアウトしました');
    }
}
