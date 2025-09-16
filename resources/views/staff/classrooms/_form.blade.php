@php
    /** @var \App\Models\Classroom $classroom */
@endphp

@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">教室番号 <span class="text-red-500">*</span></label>
        <input type="text" name="code" value="{{ old('code', $classroom->code) }}" class="mt-1 w-full border rounded p-2" required>
        @error('code') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">教室名 <span class="text-red-500">*</span></label>
        <input type="text" name="name" value="{{ old('name', $classroom->name) }}" class="mt-1 w-full border rounded p-2" required>
        @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">郵便番号</label>
        <input type="text" name="postal_code" placeholder="例: 123-4567" value="{{ old('postal_code', $classroom->postal_code) }}" class="mt-1 w-full border rounded p-2">
        @error('postal_code') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">電話番号</label>
        <input type="text" name="tel" placeholder="例: 03-1234-5678" value="{{ old('tel', $classroom->tel) }}" class="mt-1 w-full border rounded p-2">
        @error('tel') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700">住所</label>
        <input type="text" name="address" value="{{ old('address', $classroom->address) }}" class="mt-1 w-full border rounded p-2">
        @error('address') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700">メールアドレス</label>
        <input type="email" name="email" value="{{ old('email', $classroom->email) }}" class="mt-1 w-full border rounded p-2">
        @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <button class="bg-indigo-600 text-white px-4 py-2 rounded">保存</button>
    <a href="{{ route('staff.classrooms.index') }}" class="text-gray-600 hover:underline">キャンセル</a>
</div>
