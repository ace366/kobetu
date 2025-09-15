<x-app-layout>
  <div class="max-w-5xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">祝日・特別休暇 一覧</h1>

    @if (session('status'))
      <div class="mb-3 p-2 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
    @endif

    <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-3 mb-4">
      <div>
        <label class="text-sm text-gray-600">年</label>
        <input type="number" name="year" value="{{ $year }}" class="w-full border rounded p-2" min="2000" max="2100">
      </div>
      <div>
        <label class="text-sm text-gray-600">月</label>
        <input type="number" name="month" value="{{ $month }}" class="w-full border rounded p-2" min="1" max="12">
      </div>
      <div>
        <label class="text-sm text-gray-600">区分</label>
        <select name="category" class="w-full border rounded p-2">
          <option value="">すべて</option>
          @foreach(['national'=>'祝日','gw'=>'GW','obon'=>'お盆','yearend'=>'年末年始','other'=>'その他'] as $k=>$v)
            <option value="{{ $k }}" @selected($category===$k)>{{ $v }}</option>
          @endforeach
        </select>
      </div>
      <div class="md:col-span-2 flex items-end gap-2">
        <button class="bg-indigo-600 text-white px-4 py-2 rounded">検索</button>
        <a href="{{ route('staff.holidays.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">新規登録</a>
      </div>
    </form>

    <div class="bg-white rounded shadow">
      <table class="w-full text-sm">
        <thead>
          <tr class="bg-gray-50 text-left">
            <th class="p-2 border">日付</th>
            <th class="p-2 border">名称</th>
            <th class="p-2 border">区分</th>
            <th class="p-2 border w-40">操作</th>
          </tr>
        </thead>
        <tbody>
          @forelse($holidays as $h)
            <tr>
              <td class="p-2 border">{{ $h->date->format('Y-m-d') }}</td>
              <td class="p-2 border">{{ $h->name }}</td>
              <td class="p-2 border">{{ ['national'=>'祝日','gw'=>'GW','obon'=>'お盆','yearend'=>'年末年始','other'=>'その他'][$h->category] ?? $h->category }}</td>
              <td class="p-2 border">
                <a href="{{ route('staff.holidays.edit',$h) }}" class="text-indigo-600">編集</a>
                <form action="{{ route('staff.holidays.destroy',$h) }}" method="POST" class="inline" onsubmit="return confirm('削除しますか？')">
                  @csrf @method('DELETE')
                  <button class="text-red-600 ml-2">削除</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="4" class="p-4 text-center text-gray-500">データがありません</td></tr>
          @endforelse
        </tbody>
      </table>
      <div class="p-2">{{ $holidays->links() }}</div>
    </div>
    <div class="mt-6 bg-white rounded shadow p-4" id="import">
      <h2 class="font-semibold mb-2">APIから祝日を取り込む（excelapi.org）</h2>
      <form method="POST" action="{{ route('staff.holidays.import') }}" class="grid grid-cols-1 md:grid-cols-5 gap-3">
        @csrf
        <div>
          <label class="text-sm text-gray-600">年</label>
          <input type="number" name="year" class="w-full border rounded p-2" value="{{ $year }}" min="2000" max="2100" required>
        </div>
        <div class="flex items-center gap-2 md:col-span-3">
          <label class="inline-flex items-center">
            <input type="checkbox" name="overwrite" value="1" class="mr-2" checked>
            既存データを上書きする
          </label>
        </div>
        <div class="md:col-span-1 flex items-end">
          <button class="bg-amber-600 text-white px-4 py-2 rounded">API取込</button>
        </div>
      </form>
    </div>

    <div class="mt-6 bg-white rounded shadow p-4">
      <h2 class="font-semibold mb-2">期間一括登録（GW / お盆 / 年末年始など）</h2>
      <form method="POST" action="{{ route('staff.holidays.bulk') }}" class="grid grid-cols-1 md:grid-cols-5 gap-3">
        @csrf
        <div>
          <label class="text-sm text-gray-600">開始日</label>
          <input type="date" name="start_date" class="w-full border rounded p-2" required>
        </div>
        <div>
          <label class="text-sm text-gray-600">終了日</label>
          <input type="date" name="end_date" class="w-full border rounded p-2" required>
        </div>
        <div>
          <label class="text-sm text-gray-600">名称</label>
          <input type="text" name="name" class="w-full border rounded p-2" placeholder="例: GW 休校" required>
        </div>
        <div>
          <label class="text-sm text-gray-600">区分</label>
          <select name="category" class="w-full border rounded p-2" required>
            @foreach(['gw'=>'GW','obon'=>'お盆','yearend'=>'年末年始','national'=>'祝日','other'=>'その他'] as $k=>$v)
              <option value="{{ $k }}">{{ $v }}</option>
            @endforeach
          </select>
        </div>
        <div class="md:col-span-1 flex items-end">
          <button class="bg-green-600 text-white px-4 py-2 rounded">一括登録</button>
        </div>
      </form>
    </div>
  </div>
</x-app-layout>