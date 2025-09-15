<x-app-layout>
    <div class="max-w-5xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">月間カレンダー（生徒別プレビュー）</h1>

        {{-- フィルタ（年・月・生徒） --}}
        <form method="GET" action="{{ route('staff.calendars.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-4">
            <div>
                <label class="text-sm text-gray-600">年</label>
                <input type="number" name="year" value="{{ $year }}" class="w-full border rounded p-2" min="2000" max="2100">
            </div>
            <div>
                <label class="text-sm text-gray-600">月</label>
                <input type="number" name="month" value="{{ $month }}" class="w-full border rounded p-2" min="1" max="12">
            </div>
            <div class="md:col-span-2">
                <label class="text-sm text-gray-600">生徒</label>
                <select name="student_id" class="w-full border rounded p-2">
                    <option value="">選択してください</option>
                    @foreach($students as $s)
                        <option value="{{ $s->id }}" {{ optional($student)->id === $s->id ? 'selected' : '' }}>
                            {{ $s->last_name }} {{ $s->first_name }}（{{ $s->school?->name ?? '学校未設定' }} / {{ $s->grade ?? '学年未設定' }}）
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-4">
                <button class="bg-indigo-600 text-white px-4 py-2 rounded">表示</button>
                @if($student)
                    <a class="ml-2 bg-green-600 text-white px-4 py-2 rounded" target="_blank"
                       href="{{ route('staff.calendars.pdf', ['student_id'=>$student->id,'year'=>$year,'month'=>$month]) }}">PDFダウンロード</a>
                @endif
            </div>
        </form>

        @if($student)
            <div class="bg-white rounded shadow p-4">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <div class="text-lg font-semibold">{{ $year }}年 {{ $month }}月 カレンダー</div>
                        <div class="text-sm text-gray-600">
                            生徒：{{ $student->last_name }} {{ $student->first_name }}　
                            ／ 学校：{{ $student->school?->name ?? '未設定' }}　
                            ／ 学年：{{ $student->grade ?? '未設定' }}
                        </div>
                    </div>
                    <div class="text-sm text-gray-500">日曜・祝日・個別休はグレー表示</div>
                </div>

                <table class="w-full text-sm border border-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="border p-2 text-red-600">日</th>
                            <th class="border p-2">月</th>
                            <th class="border p-2">火</th>
                            <th class="border p-2">水</th>
                            <th class="border p-2">木</th>
                            <th class="border p-2">金</th>
                            <th class="border p-2 text-blue-600">土</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($grid as $week)
                            <tr>
                                @foreach($week as $cell)
                                    @php
                                        $classes = 'align-top h-24 border p-2 ';
                                        if(!$cell['in_month']) $classes .= 'bg-gray-50 text-gray-400 ';
                                        if($cell['is_off'])   $classes .= 'bg-gray-200 ';
                                    @endphp
                                    <td class="{{ $classes }}">
                                        <div class="font-semibold text-sm">{{ $cell['date']->day }}</div>
                                        @if($cell['holiday'])
                                            <div class="text-xs text-gray-700 mt-1">★{{ $cell['holiday']['name'] }}</div>
                                        @endif
                                        @if($cell['student_off'])
                                            <div class="text-xs text-gray-700 mt-1">休：{{ $cell['student_off']['reason'] ?? '個別休' }}</div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>
