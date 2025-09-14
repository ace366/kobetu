{{-- resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>生徒管理システム</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    {{-- ヘッダー --}}
    @include('layouts.student_nav')

    {{-- メインコンテンツ --}}
    <main class="flex-1 max-w-5xl mx-auto p-6">
        @if(session('status'))
            <div class="mb-4 text-green-600 font-semibold">
                {{ session('status') }}
            </div>
        @endif

        <h1 class="text-2xl font-bold mb-6">生徒用ホーム画面</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('students.create') }}" class="p-6 bg-white rounded shadow hover:bg-blue-50">
                <h2 class="text-lg font-semibold">新規生徒登録</h2>
                <p class="text-gray-500 mt-2 text-sm">新しい生徒を登録します。</p>
            </a>

            <a href="{{ route('dashboard') }}" class="p-6 bg-white rounded shadow hover:bg-blue-50">
                <h2 class="text-lg font-semibold">ダッシュボード</h2>
                <p class="text-gray-500 mt-2 text-sm">生徒一覧や統計情報を確認します。</p>
            </a>

            <a href="{{ route('profile.edit') }}" class="p-6 bg-white rounded shadow hover:bg-blue-50">
                <h2 class="text-lg font-semibold">プロフィール設定</h2>
                <p class="text-gray-500 mt-2 text-sm">登録情報を変更します。</p>
            </a>
        </div>
    </main>

    {{-- フッター --}}
    <footer class="bg-white text-center py-4 border-t text-gray-500 text-sm">
        © 個別教室のトライ / Trygroup Inc. All rights reserved.
    </footer>

</body>
</html>
