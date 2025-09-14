<x-layouts.student_nav>
<div class="max-w-5xl mx-auto p-6">
<h1 class="text-2xl font-bold mb-4">彩さんプロンプト管理</h1>


@if(session('status'))
<div class="mb-4 rounded-md border border-green-200 bg-green-50 text-green-800 px-4 py-3">
{{ session('status') }}
</div>
@endif


<div class="mb-4 flex justify-end">
<a href="{{ route('ai.prompts.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded">新規作成</a>
</div>


<div class="bg-white shadow rounded overflow-hidden">
<table class="min-w-full text-sm">
<thead class="bg-gray-50">
<tr>
<th class="px-3 py-2 text-left">ID</th>
<th class="px-3 py-2 text-left">スコープ</th>
<th class="px-3 py-2 text-left">対象生徒</th>
<th class="px-3 py-2 text-left">タイトル</th>
<th class="px-3 py-2 text-left">モデル</th>
<th class="px-3 py-2 text-left">状態</th>
<th class="px-3 py-2 text-left">操作</th>
</tr>
</thead>
<tbody>
@foreach($prompts as $p)
<tr class="border-t">
<td class="px-3 py-2">{{ $p->id }}</td>
<td class="px-3 py-2">{{ strtoupper($p->scope) }}</td>
<td class="px-3 py-2">{{ $p->student_id ?: '-' }}</td>
<td class="px-3 py-2">{{ $p->title }}</td>
<td class="px-3 py-2">{{ $p->model ?: 'env既定' }}</td>
<td class="px-3 py-2">{!! $p->is_active ? '<span class="text-green-700">有効</span>' : '<span class="text-gray-500">無効</span>' !!}</td>
<td class="px-3 py-2 space-x-2">
<a class="text-blue-600" href="{{ route('ai.prompts.edit', $p) }}">編集</a>
<form class="inline" method="POST" action="{{ route('ai.prompts.toggle', $p) }}">
@csrf @method('PATCH')
<button class="text-indigo-600" type="submit">切替</button>
</form>
<form class="inline" method="POST" action="{{ route('ai.prompts.destroy', $p) }}" onsubmit="return confirm('削除しますか？');">
@csrf @method('DELETE')
<button class="text-red-600" type="submit">削除</button>
</form>
</td>
</tr>
@endforeach
</tbody>
</table>
</div>


<div class="mt-3">{{ $prompts->links() }}</div>
</div>
</x-layouts.student_nav>