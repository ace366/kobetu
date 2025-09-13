<x-guest-layout>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-center mb-6">ログイン</h2>

            @if($errors->any())
                <div class="mb-4 text-red-600 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.attempt') }}">
                @csrf
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium">ID（メールアドレス）</label>
                    <input id="email" type="email" name="email" required autofocus
                           class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium">パスワード</label>
                    <input id="password" type="password" name="password" required
                           class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                </div>

                <button type="submit"
                        class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700">
                    ログイン
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
