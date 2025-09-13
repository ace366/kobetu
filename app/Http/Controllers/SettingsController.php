<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    public function __construct()
    {
        // 認証必須
        $this->middleware('auth');
    }

    public function index()
    {
        return view('settings.index');
    }

    public function createUser()
    {
        return view('settings.create-user');
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:4',
            'role'     => 'required|in:admin,user',
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
        ]);

        return redirect()->route('settings.index')->with('status', '新規アカウントを作成しました。');
    }
        // ユーザー一覧
    public function listUsers()
    {
        $users = User::orderBy('id')->paginate(10);
        return view('settings.index-users', compact('users'));
    }

    // 編集フォーム
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('settings.edit-user', compact('user'));
    }

    // 更新処理
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required','email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|min:4', // 未入力なら変更なし
            'role'     => 'required|in:admin,user',
        ]);

        $user->name  = $validated['name'];
        $user->email = $validated['email'];
        $user->role  = $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('settings.listUsers')->with('status', 'ユーザー情報を更新しました。');
    }
}
