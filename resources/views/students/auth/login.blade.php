<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>生徒ログイン</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-white shadow rounded p-6">
        <h2 class="text-xl font-bold mb-4 text-center">生徒ログイン</h2>

        @if(session('status'))
            <div class="mb-4 text-green-600 text-sm">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('student.login.attempt') }}">
            @csrf
            <div class="mb-3">
                <label class="block mb-1">メールアドレス</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="w-full border rounded px-3 py-2" required autofocus>
                @error('email')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-3">
                <label class="block mb-1">パスワード</label>
                <input type="password" name="password"
                       class="w-full border rounded px-3 py-2" required>
                @error('password')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center mb-3">
                <input type="checkbox" name="remember" id="remember" class="mr-2">
                <label for="remember">ログイン状態を保持する</label>
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                ログイン
            </button>
        </form>
    </div>
</body>
</html>
