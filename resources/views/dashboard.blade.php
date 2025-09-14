<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
        <div class="mt-2 space-x-4">
            <a href="{{ route('register.create') }}" class="text-blue-600 hover:underline">新規登録</a>
            <a href="{{ route('settings.listUsers') }}" class="text-blue-600 hover:underline">ユーザー一覧</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- ユーザー一覧テーブル --}}
                    <table class="w-full border border-gray-300 text-sm">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-700">
                                <th class="border px-2 py-1">ID</th>
                                <th class="border px-2 py-1">名前</th>
                                <th class="border px-2 py-1">メール</th>
                                <th class="border px-2 py-1">役割</th>
                                <th class="border px-2 py-1">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td class="border px-2 py-1">{{ $user->id }}</td>
                                    <td class="border px-2 py-1">{{ $user->name }}</td>
                                    <td class="border px-2 py-1">{{ $user->email }}</td>
                                    <td class="border px-2 py-1">{{ $user->role }}</td>
                                    <td class="border px-2 py-1">
                                        <a href="{{ route('settings.editUser', $user->id) }}" class="text-blue-600 hover:underline">編集</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

{{-- Vite を使わない構成なら CDN でOK --}}
<script src="https://cdn.tailwindcss.com"></script>
