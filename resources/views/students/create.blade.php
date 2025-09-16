<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>生徒登録</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-lg bg-white shadow rounded p-6">
        <h2 class="text-xl font-bold mb-4">生徒情報 登録</h2>

        @if(session('status'))
            <div class="mb-4 text-green-600">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('students.store') }}">
            @csrf
            <div class="grid grid-cols-2 gap-4 mb-3">
                <div>
                    <label class="block mb-1">姓（漢字）</label>
                    <input type="text" name="last_name" class="w-full border rounded px-2 py-1" required>
                </div>
                <div>
                    <label class="block mb-1">名（漢字）</label>
                    <input type="text" name="first_name" class="w-full border rounded px-2 py-1" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-3">
                <div>
                    <label class="block mb-1">姓（かな）</label>
                    <input type="text" name="last_name_kana" class="w-full border rounded px-2 py-1" required>
                </div>
                <div>
                    <label class="block mb-1">名（かな）</label>
                    <input type="text" name="first_name_kana" class="w-full border rounded px-2 py-1" required>
                </div>
            </div>

<div class="mb-3">
    <label class="block mb-1">学校名</label>
    <input type="text" id="school-input" 
           class="w-full border rounded px-2 py-1" 
           placeholder="学校を入力してください" autocomplete="off" required>

    {{-- 実際にDBに保存するのは hidden の school_id --}}
    <input type="hidden" name="school_id" id="school-id">

    <ul id="school-suggestions" class="border bg-white mt-1 rounded shadow hidden"></ul>
</div>

            <div class="mb-3">
                <label class="block mb-1">学年</label>
                <select name="grade" class="w-full border rounded px-2 py-1">
                    @for($i = 1; $i <= 6; $i++)
                        <option value="{{ $i }}年生">{{ $i }}年生</option>
                    @endfor
                </select>
            </div>

            <div class="mb-3">
                <label class="block mb-1">電話番号</label>
                <input type="text" name="phone" class="w-full border rounded px-2 py-1">
            </div>

            <div class="mb-3">
                <label class="block mb-1">メールアドレス</label>
                <input type="email" name="email" class="w-full border rounded px-2 py-1" required>
            </div>

            <div class="mb-3">
                <label class="block mb-1">パスワード</label>
                <input type="password" name="password" class="w-full border rounded px-2 py-1" required>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                登録する
            </button>
        </form>
    </div>
</body>
</html>

<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/@tailwindcss/line-clamp@latest"></script>
<script>
document.getElementById('school').addEventListener('input', function() {
    let q = this.value;
    let list = document.getElementById('school-list');
    let select = document.getElementById('school-select');

    if (q.length < 1) {
        list.classList.add('hidden');
        select.classList.add('hidden');
        return;
    }

    fetch(`/kobetu/public/api/schools?q=${encodeURIComponent(q)}`)
        .then(res => res.json())
        .then(data => {
            list.innerHTML = '';
            select.innerHTML = '';

            if (data.length === 0) {
                list.classList.add('hidden');
                select.classList.add('hidden');
                return;
            }

            if (data.length <= 10) {
                // 通常の候補リスト
                data.forEach(school => {
                    let li = document.createElement('li');
                    li.textContent = `${school.name} (${school.city ?? ''} ${school.prefecture ?? ''})`;
                    li.classList.add('px-2','py-1','cursor-pointer','hover:bg-gray-200');
                    li.onclick = function() {
                        document.getElementById('school').value = school.name;
                        list.classList.add('hidden');
                    };
                    list.appendChild(li);
                });
                list.classList.remove('hidden');
                select.classList.add('hidden');
            } else {
                // プルダウンに切り替え
                let defaultOption = document.createElement('option');
                defaultOption.textContent = "候補が多いため選択してください";
                defaultOption.disabled = true;
                defaultOption.selected = true;
                select.appendChild(defaultOption);

                data.forEach(school => {
                    let option = document.createElement('option');
                    option.value = school.name;
                    option.textContent = `${school.name} (${school.city ?? ''} ${school.prefecture ?? ''})`;
                    select.appendChild(option);
                });

                select.onchange = function() {
                    document.getElementById('school').value = this.value;
                };

                select.classList.remove('hidden');
                list.classList.add('hidden');
            }
        });
});

</script>
<script>
const input = document.getElementById('school-input');
const hidden = document.getElementById('school-id');
const suggestions = document.getElementById('school-suggestions');

input.addEventListener('input', function() {
    let q = this.value;
    suggestions.innerHTML = '';
    if (q.length < 1) {
        suggestions.classList.add('hidden');
        return;
    }

    fetch(`/kobetu/public/api/schools?q=${encodeURIComponent(q)}`)
        .then(res => res.json())
        .then(data => {
            if (data.length === 0) {
                suggestions.classList.add('hidden');
                return;
            }
            data.forEach(school => {
                let li = document.createElement('li');
                li.textContent = `${school.name} (${school.city ?? ''} ${school.prefecture ?? ''})`;
                li.classList.add('px-2','py-1','cursor-pointer','hover:bg-gray-200');
                li.onclick = function() {
                    input.value = school.name;
                    hidden.value = school.id; // ← ここでIDをセット！
                    suggestions.classList.add('hidden');
                };
                suggestions.appendChild(li);
            });
            suggestions.classList.remove('hidden');
        });
});
</script>
