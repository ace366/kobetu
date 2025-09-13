<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ユーザー編集</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-white shadow rounded p-6">
        <h2 class="text-xl font-bold mb-4">ユーザー編集</h2>

        <form method="POST" action="{{ route('settings.updateUser', $user->id) }}">
            @csrf
            @method('PATCH')

            <div class="mb-3">
                <label class="block mb-1">名前</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-3">
                <label class="block mb-1">メールアドレス</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-3">
                <label class="block mb-1">パスワード (変更する場合のみ入力)</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-3">
                <label class="block mb-1">権限</label>
                <select name="role" class="w-full border rounded px-3 py-2">
                    <option value="user" @if($user->role === 'user') selected @endif>ユーザー</option>
                    <option value="admin" @if($user->role === 'admin') selected @endif>管理者</option>
                </select>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                更新する
            </button>
        </form>
    </div>
</body>
</html>
<!-- Tailwind CDN（開発用） -->
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/@tailwindcss/line-clamp@latest"></script>