{{-- resources/views/layouts/student_nav.blade.php --}}
<nav class="bg-white shadow px-4 py-3 flex justify-between items-center">
    <div class="flex space-x-6">
        <a href="{{ route('welcome') }}" class="text-gray-700 hover:text-blue-600">ホーム</a>
        <a href="{{ route('students.create') }}" class="text-gray-700 hover:text-blue-600">新規登録</a>
        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600">ダッシュボード</a>
    </div>

    @auth
    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
            <span class="text-gray-700">{{ Auth::user()->name }}</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div x-show="open" @click.away="open = false"
             class="absolute right-0 mt-2 w-40 bg-white border rounded shadow-lg py-1 z-20">
            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                プロフィール変更
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                    ログアウト
                </button>
            </form>
        </div>
    </div>
    @endauth
</nav>
