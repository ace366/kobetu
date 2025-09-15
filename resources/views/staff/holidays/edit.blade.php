<x-app-layout>
  <div class="max-w-xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">祝日・特別休暇 編集</h1>

    @if ($errors->any())
      <div class="mb-3 text-red-600 text-sm">
        <ul class="list-disc pl-5">
          @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('staff.holidays.update',$holiday) }}" class="space-y-3">
      @csrf @method('PUT')
      <div>
        <label class="text-sm text-gray-600">日付</label>
        <input type="date" name="date" class="w-full border rounded p-2" value="{{ $holiday->date->format('Y-m-d') }}" required>
      </div>
      <div>
        <label class="text-sm text-gray-600">名称</label>
        <input type="text" name="name" class="w-full border rounded p-2" value="{{ $holiday->name }}" required>
      </div>
      <div>
        <label class="text-sm text-gray-600">区分</label>
        <select name="category" class="w-full border rounded p-2" required>
          @foreach(['national'=>'祝日','gw'=>'GW','obon'=>'お盆','yearend'=>'年末年始','other'=>'その他'] as $k=>$v)
            <option value="{{ $k }}" @selected($holiday->category===$k)>{{ $v }}</option>
          @endforeach
        </select>
      </div>
      <div class="pt-2">
        <button class="bg-blue-600 text-white px-4 py-2 rounded">更新</button>
        <a href="{{ route('staff.holidays.index') }}" class="ml-2 text-gray-600">戻る</a>
      </div>
    </form>
  </div>
</x-app-layout>