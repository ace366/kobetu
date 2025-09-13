<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>設定画面</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center">
    <div class="w-full max-w-lg bg-white shadow rounded p-6">
        <h1 class="text-2xl font-bold mb-6">設定画面</h1>

        @if(session('status'))
            <div class="mb-4 text-green-600">{{ session('status') }}</div>
        @endif

        <ul class="space-y-4">
            <li>
                <a href="{{ route('settings.createUser') }}" class="text-blue-600 hover:underline">
                    ➕ 新規アカウント作成
                </a>
            </li>
        </ul>
    </div>
</body>
</html>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/@tailwindcss/line-clamp@latest"></script>