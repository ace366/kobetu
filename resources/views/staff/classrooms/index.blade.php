<x-app-layout>
    <div class="max-w-6xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">教室情報 一覧</h1>

        @if(session('status'))
            <div class="mb-4 rounded-md border border-green-200 bg-green-50 text-green-800 px-4 py-3">
                {{ session('status') }}
            </div>
        @endif

        <div class="flex items-center justify-between mb-4">
            <form method="GET" action="{{ route('staff.classrooms.index') }}" class="flex items-center gap-2">
                <input type="text" name="q" value="{{ $q }}" placeholder="番号/名称/住所/メール で検索" class="border rounded p-2 w-64">
                <button class="bg-slate-600 text-white px-3 py-2 rounded">検索</button>
                @if($q !== '')
                    <a href="{{ route('staff.classrooms.index') }}" class="text-sm text-gray-600 hover:underline">クリア</a>
                @endif
            </form>

            <a href="{{ route('staff.classrooms.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded">新規登録</a>
        </div>

        <div class="bg-white rounded shadow overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-50">
                    <tr class="text-left text-sm text-gray-600">
                        <th class="px-4 py-2">教室番号</th>
                        <th class="px-4 py-2">教室名</th>
                        <th class="px-4 py-2">郵便番号</th>
                        <th class="px-4 py-2">住所</th>
                        <th class="px-4 py-2">電話</th>
                        <th class="px-4 py-2">メール</th>
                        <th class="px-4 py-2 w-40">操作</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($classrooms as $c)
                        <tr class="border-t">
                            <td class="px-4 py-2 font-mono">{{ $c->code }}</td>
                            <td class="px-4 py-2">{{ $c->name }}</td>
                            <td class="px-4 py-2">{{ $c->postal_code }}</td>
                            <td class="px-4 py-2">{{ $c->address }}</td>
                            <td class="px-4 py-2">{{ $c->tel }}</td>
                            <td class="px-4 py-2">{{ $c->email }}</td>
                            <td class="px-4 py-2">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('staff.classrooms.edit', $c) }}" class="px-3 py-1 rounded bg-amber-500 text-white">編集</a>
                                    <form method="POST" action="{{ route('staff.classrooms.destroy', $c) }}"
                                          onsubmit="return confirm('削除しますか？この操作は取り消せません。');">
                                        @csrf @method('DELETE')
                                        <button class="px-3 py-1 rounded bg-rose-600 text-white">削除</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-6 text-center text-gray-500">データがありません</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $classrooms->links() }}
        </div>
    </div>
</x-app-layout>
