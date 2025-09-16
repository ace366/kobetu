<x-app-layout>
    <div class="max-w-3xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">教室情報 編集</h1>

        <div class="bg-white rounded shadow p-4">
            <form method="POST" action="{{ route('staff.classrooms.update', $classroom) }}">
                @method('PUT')
                @include('staff.classrooms._form', ['classroom' => $classroom])
            </form>
        </div>
    </div>
</x-app-layout>
