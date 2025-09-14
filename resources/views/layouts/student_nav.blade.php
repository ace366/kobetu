<div class="min-h-screen bg-gray-100">
    {{-- ヘッダー --}}
    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                {{-- 左：ロゴ --}}
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="text-lg font-bold text-blue-600">
                        個別教室のトライ
                    </a>
                </div>

                {{-- 右：ユーザー名 + ドロップダウン or ログイン --}}
                <div class="flex items-center">
                    @auth
                        {{-- ログイン中 --}}
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center text-gray-700 hover:text-gray-900">
                                <span class="mr-2">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false"
                                 class="absolute right-0 mt-2 w-40 bg-white border rounded shadow">
                                <a href="{{ route('profile.edit') }}" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    プロフィール変更
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        ログアウト
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endauth

                    @guest
                        {{-- ログアウト中（未ログイン） --}}
                        <a href="{{ route('login') }}" 
                           class="text-sm text-gray-700 hover:text-blue-600 px-3 py-2 rounded">
                            ログイン
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    {{-- ページ本体 --}}
    <main>
        {{ $header ?? '' }}
        <div class="py-6">
            {{ $slot }}
        </div>
    </main>
</div>

{{-- Alpine.js が必要 --}}
<script src="//unpkg.com/alpinejs" defer></script>
