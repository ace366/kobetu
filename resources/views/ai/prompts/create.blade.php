<x-layouts.student_nav>
<div class="max-w-4xl mx-auto p-6">
<h1 class="text-2xl font-bold mb-4">プロンプト新規作成</h1>
<form method="POST" action="{{ route('ai.prompts.store') }}">
@include('ai.prompts._form')
</form>
</div>
</x-layouts.student_nav>