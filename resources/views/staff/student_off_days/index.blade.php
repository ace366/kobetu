<x-app-layout>
  <div class="max-w-6xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">生徒の個別休 一覧</h1>

    @if (session('status'))
      <div class="mb-3 p-2 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
    @endif

    <form method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-3 mb-4">
      <div class="md:col-span-2">
        <label class="text-sm text-gray-600">生徒</label>
        <select name="student_id" class="w-full border rounded p-2">
          <option value="">すべて</option>
          @foreach($students as $s)
            <option value="{{ $s->id }}" @selected($studentId==$s->id)>
              {{ $s->last_name }} {{ $s->first_name }}（{{ $s->school?->name ?? '学校未設定' }}）
            </option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="text-sm text-gray-600">年</label>
        <input type="number" name="year" value="{{ $year }}" class="w-full border rounded p-2" min="2000" max="2100">
      </div>
      <div>
        <label class="text-sm text-gray-600">月</label>
        <input type="number" name="month" value="{{ $month }}" class="w-full border rounded p-2" min="1" max="12">
      </div>
      <div class="md:col-span-2 flex items-end gap-2">
        <button class="bg-indigo-600 text-white px-4 py-2 rounded">検索</button>
        <a href="{{ route('staff.student_off_days.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">新規登録</a>
      </div>
    </form>

    <div class="bg-white rounded shadow">
      <table class="w-full text-sm">
        <thead>
          <tr class="bg-gray-50 text-left">
            <th class="p-2 border">日付</th>
            <th class="p-2 border">生徒</th>
            <th class="p-2 border">学校</th>
            <th class="p-2 border">理由</th>
            <th class="p-2 border w-40">操作</th>
          </tr>
        </thead>
        <tbody>
          @forelse($rows as $r)
            <tr>
              <td class="p-2 border">{{ $r->date->format('Y-m-d') }}</td>
              <td class="p-2 border">{{ $r->student->last_name }} {{ $r->student->first_name }}</td>
              <td class="p-2 border">{{ $r->student->school?->name ?? '未設定' }}</td>
              <td class="p-2 border">{{ $r->reason }}</td>
              <td class="p-2 border">
                <a href="{{ route('staff.student_off_days.edit',$r) }}" class="text-indigo-600">編集</a>
                <form action="{{ route('staff.student_off_days.destroy',$r) }}" method="POST" class="inline" onsubmit="return confirm('削除しますか？')">
                  @csrf @method('DELETE')
                  <button class="text-red-600 ml-2">削除</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="5" class="p-4 text-center text-gray-500">データがありません</td></tr>
          @endforelse
        </tbody>
      </table>
      <div class="p-2">{{ $rows->links() }}</div>
    </div>

    <div class="mt-6 bg-white rounded shadow p-4">
      <h2 class="font-semibold mb-2">期間一括登録（同一生徒）</h2>
      <form method="POST" action="{{ route('staff.student_off_days.bulk') }}" class="grid grid-cols-1 md:grid-cols-6 gap-3">
        @csrf
        <div class="md:col-span-2">
          <label class="text-sm text-gray-600">生徒</label>
          <select name="student_id" class="w-full border rounded p-2" required>
            @foreach($students as $s)
              <option value="{{ $s->id }}">{{ $s->last_name }} {{ $s->first_name }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="text-sm text-gray-600">開始日</label>
          <input type="date" name="start_date" class="w-full border rounded p-2" required>
        </div>
        <div>
          <label class="text-sm text-gray-600">終了日</label>
          <input type="date" name="end_date" class="w-full border rounded p-2" required>
        </div>
        <div class="md:col-span-2">
          <label class="text-sm text-gray-600">理由</label>
          <input type="text" name="reason" class="w-full border rounded p-2" placeholder="任意">
        </div>
        <div class="md:col-span-1 flex items-end">
          <button class="bg-green-600 text-white px-4 py-2 rounded">一括登録</button>
        </div>
      </form>
    </div>
  </div>
</x-app-layout>