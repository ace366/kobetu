<?php

namespace App\Http\Controllers;

use App\Models\AiPrompt;
use App\Models\Student;
use Illuminate\Http\Request;

class AIPromptController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!$this->isStaff()) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index()
    {
        $prompts = AiPrompt::orderByDesc('id')->paginate(20);
        return view('ai.prompts.index', compact('prompts'));
    }

    public function create()
    {
        $students = Student::orderBy('last_name')->orderBy('first_name')->limit(200)->get();
        return view('ai.prompts.create', compact('students'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'scope'           => 'required|in:global,student',
            'student_id'      => 'nullable|exists:students,id',
            'title'           => 'required|string|max:255',
            'system_prompt'   => 'required|string',
            'behavior_prompt' => 'nullable|string',
            'model'           => 'nullable|string|max:100',
            'temperature'     => 'nullable|numeric|min:0|max:2',
            'top_p'           => 'nullable|numeric|min:0|max:1',
            'is_active'       => 'sometimes|boolean',
            'effective_from'  => 'nullable|date',
            'effective_to'    => 'nullable|date|after_or_equal:effective_from',
        ]);

        AiPrompt::create($data + [
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('ai.prompts.index')->with('status', '登録しました');
    }

    public function edit(AiPrompt $prompt)
    {
        $students = Student::orderBy('last_name')->orderBy('first_name')->limit(200)->get();
        return view('ai.prompts.edit', compact('prompt', 'students'));
    }

    public function update(Request $request, AiPrompt $prompt)
    {
        $data = $request->validate([
            'scope'           => 'required|in:global,student',
            'student_id'      => 'nullable|exists:students,id',
            'title'           => 'required|string|max:255',
            'system_prompt'   => 'required|string',
            'behavior_prompt' => 'nullable|string',
            'model'           => 'nullable|string|max:100',
            'temperature'     => 'nullable|numeric|min:0|max:2',
            'top_p'           => 'nullable|numeric|min:0|max:1',
            'is_active'       => 'sometimes|boolean',
            'effective_from'  => 'nullable|date',
            'effective_to'    => 'nullable|date|after_or_equal:effective_from',
        ]);

        $prompt->update($data + [
            'is_active' => $request->boolean('is_active', $prompt->is_active),
        ]);

        return redirect()->route('ai.prompts.index')->with('status', '更新しました');
    }

    public function destroy(AiPrompt $prompt)
    {
        $prompt->delete();
        return back()->with('status', '削除しました');
    }

    public function toggle(AiPrompt $prompt)
    {
        $prompt->is_active = !$prompt->is_active;
        $prompt->save();
        return back()->with('status', '状態を切り替えました');
    }

    private function isStaff(): bool
    {
        $u = auth()->user();
        return $u && in_array(data_get($u, 'role'), ['admin','teacher','staff'], true);
    }
}
