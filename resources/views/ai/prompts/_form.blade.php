@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
<div>
<label class="block text-sm text-gray-600">スコープ</label>
<select name="scope" class="border rounded w-full p-2" required>
<option value="global" {{ old('scope', $prompt->scope ?? 'global')==='global'?'selected':'' }}>GLOBAL</option>
<option value="student" {{ old('scope', $prompt->scope ?? '')==='student'?'selected':'' }}>STUDENT</option>
</select>
</div>
<div>
<label class="block text-sm text-gray-600">対象生徒ID（scope=studentの時）</label>
<input type="number" name="student_id" value="{{ old('student_id', $prompt->student_id ?? '') }}" class="border rounded w-full p-2">
</div>
<div class="md:col-span-2">
<label class="block text-sm text-gray-600">タイトル</label>
<input type="text" name="title" value="{{ old('title', $prompt->title ?? '') }}" class="border rounded w-full p-2" required>
</div>
<div class="md:col-span-2">
<label class="block text-sm text-gray-600">システムプロンプト</label>
<textarea name="system_prompt" rows="6" class="border rounded w-full p-2" required>{{ old('system_prompt', $prompt->system_prompt ?? '') }}</textarea>
</div>
<div class="md:col-span-2">
<label class="block text-sm text-gray-600">口調/追加ルール（任意）</label>
<textarea name="behavior_prompt" rows="4" class="border rounded w-full p-2">{{ old('behavior_prompt', $prompt->behavior_prompt ?? '') }}</textarea>
</div>
<div>
<label class="block text-sm text-gray-600">モデル（空ならenv既定）</label>
<input type="text" name="model" value="{{ old('model', $prompt->model ?? '') }}" class="border rounded w-full p-2" placeholder="gpt-4o-mini 等">
</div>
<div>
<label class="block text-sm text-gray-600">temperature</label>
<input type="number" step="0.01" min="0" max="2" name="temperature" value="{{ old('temperature', $prompt->temperature ?? 0.7) }}" class="border rounded w-full p-2">
</div>
<div>
<label class="block text-sm text-gray-600">top_p</label>
<input type="number" step="0.01" min="0" max="1" name="top_p" value="{{ old('top_p', $prompt->top_p ?? 1.0) }}" class="border rounded w-full p-2">
</div>
<div class="flex items-center space-x-2">
<input id="is_active" type="checkbox" name="is_active" value="1" {{ old('is_active', ($prompt->is_active ?? true))? 'checked':'' }}>
<label for="is_active">有効</label>
</div>
<div>
<label class="block text-sm text-gray-600">適用開始</label>
<input type="datetime-local" name="effective_from" value="{{ old('effective_from', optional($prompt->effective_from ?? null)->format('Y-m-d\TH:i')) }}" class="border rounded w-full p-2">
</div>
<div>
<label class="block text-sm text-gray-600">適用終了</label>
<input type="datetime-local" name="effective_to" value="{{ old('effective_to', optional($prompt->effective_to ?? null)->format('Y-m-d\TH:i')) }}" class="border rounded w-full p-2">
</div>
</div>


<div class="mt-4">
<button class="bg-indigo-600 text-white px-4 py-2 rounded" type="submit">保存</button>
<a href="{{ route('ai.prompts.index') }}" class="ml-2 text-gray-600">戻る</a>
</div>