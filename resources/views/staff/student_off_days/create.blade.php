<x-app-layout>
  <div class="max-w-xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">生徒の個別休 新規登録</h1>

    @if ($errors->any())
      <div class="mb-3 text-red-600 text-sm">
        <ul class="list-disc pl-5">
          @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('staff.student_off_days.store') }}" class="space-y-3">
      @csrf
      <div>
        <label class="text-sm text-gray-600">生徒</label>
        <select name="student_id" class="w-full border rounded p-2" required>
          @foreach($students as $s)
            <option value="{{ $s->id }}">{{ $s->last_name }} {{ $s->first_name }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="text-sm text-gray-600">日付</label>
        <input type="date" name="date" class="w-full border rounded p-2" required>
      </div>
      <div>
        <label class="text-sm text-gray-600">理由（任意）</label>
        <input type="text" name="reason" class="w-full border rounded p-2">
      </div>
      <div class="pt-2">
        <button class="bg-blue-600 text-white px-4 py-2 rounded">登録</button>
        <a href="{{ route('staff.student_off_days.index') }}" class="ml-2 text-gray-600">戻る</a>
      </div>
    </form>
  </div>
</x-app-layout>