<?php

namespace App\Http\Controllers;

use App\Models\AiChatMessage;
use App\Models\AiChatSession;
use App\Models\AiPrompt;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AIAssistantController extends Controller
{
    /** チャット画面 */
    public function index(Request $request)
    {
        $student = $this->currentStudent($request);
        abort_if(!$student, 403, '生徒ログインが必要です');

        $session = AiChatSession::query()
            ->where('student_id', $student->id)
            ->orderByDesc('last_message_at')
            ->first();

        if (!$session) {
            $session = AiChatSession::create([
                'student_id'      => $student->id,
                'title'           => '新しい会話',
                'greeting'        => null,
                'last_message_at' => now(),
            ]);
        }

        $history  = AiChatMessage::where('session_id', $session->id)->orderBy('id')->get();
        $greeting = $session->greeting ?: $this->makeGreetingFromHistory($history);
        if (!$session->greeting && $greeting) {
            $session->greeting = $greeting;
            $session->save();
        }

        return view('ai.chat', [
            'session'  => $session,
            'history'  => $history,
            'greeting' => $greeting,
        ]);
    }

    /** 送信(非同期) */
    public function ask(Request $request)
    {
        $student = $this->currentStudent($request);
        abort_if(!$student, 403, '生徒ログインが必要です');

        $validated = $request->validate([
            'question'   => ['required','string','max:2000'],
            'session_id' => ['nullable','integer','exists:ai_chat_sessions,id'],
        ]);

        $session = $validated['session_id']
            ? AiChatSession::where('id', $validated['session_id'])->where('student_id', $student->id)->firstOrFail()
            : AiChatSession::firstOrCreate(['student_id' => $student->id], [
                'title'           => '新しい会話',
                'last_message_at' => now(),
            ]);

        // プロンプト解決
        $prompt      = AiPrompt::resolveForStudent($student->id);
        $model       = $prompt?->model ?: env('AI_CHAT_MODEL', 'gpt-4o-mini');
        $temperature = (float) ($prompt->temperature ?? env('AI_CHAT_TEMPERATURE', 0.7));
        $topP        = (float) ($prompt->top_p ?? env('AI_CHAT_TOP_P', 1.0));

        // 直近20件
        $history = AiChatMessage::where('session_id', $session->id)
            ->orderByDesc('id')->limit(20)->get()->sortBy('id')->values();

        $messages = [];
        $messages[] = ['role' => 'system', 'content' => $this->composeSystemPrompt($prompt, $student)];
        foreach ($history as $m) {
            if (in_array($m->role, ['user','assistant'], true)) {
                $messages[] = ['role' => $m->role, 'content' => $m->content];
            }
        }
        $messages[] = ['role' => 'user', 'content' => $validated['question']];

        // ユーザー発言を保存
        AiChatMessage::create([
            'session_id' => $session->id,
            'student_id' => $student->id,
            'role'       => 'user',
            'content'    => $validated['question'],
        ]);

        // 呼び出し
        $answer = '（設定エラー：管理者に連絡してください）';
        if (env('OPENAI_API_KEY')) {
            try {
                $resp = Http::timeout(45)
                    ->withToken(env('OPENAI_API_KEY'))
                    ->post('https://api.openai.com/v1/chat/completions', [
                        'model'       => $model,
                        'temperature' => $temperature,
                        'top_p'       => $topP,
                        'messages'    => $messages,
                    ]);
                $answer = $resp->successful()
                    ? (string) data_get($resp->json(), 'choices.0.message.content', '（AI応答の取得に失敗しました）')
                    : '（AI応答の取得に失敗しました）';
            } catch (\Throwable $e) {
                $answer = '（通信エラー：'.$e->getMessage().'）';
            }
        }

        // 応答を保存
        AiChatMessage::create([
            'session_id' => $session->id,
            'student_id' => $student->id,
            'role'       => 'assistant',
            'content'    => $answer,
            'model'      => $model,
        ]);

        // セッション更新
        $session->last_message_at = now();
        if (!$session->title) {
            $session->title = Str::limit($validated['question'], 40);
        }
        if (!$session->greeting) {
            $session->greeting = $this->makeGreetingFromHistory(
                AiChatMessage::where('session_id', $session->id)->orderBy('id')->get()
            );
        }
        $session->save();

        return response()->json(['answer' => $answer]);
    }

    /** Systemプロンプト構築 */
    private function composeSystemPrompt(?AiPrompt $prompt, Student $student): string
    {
        $base     = $prompt?->system_prompt ?? '';
        $behavior = $prompt?->behavior_prompt ? ("\n\n【口調/追加ルール】\n".$prompt->behavior_prompt) : '';

        $studentMeta = "\n\n【生徒情報】\n"
            .'氏名: '.trim(($student->last_name ?? '').' '.($student->first_name ?? ''))."\n"
            .'学校: '.($student->school ?? '未設定')."\n"
            .'学年: '.($student->grade ?? '未設定')."\n";

        return trim($base.$behavior.$studentMeta);
    }

    /** 200字の挨拶/要約（簡易） */
    private function makeGreetingFromHistory($history): ?string
    {
        if (!$history || (method_exists($history, 'isEmpty') && $history->isEmpty())) {
            return "はじめまして！彩(あや)です。困っている単元や目標を教えてね。200字で最近の学習状況もメモすると役立つよ。";
        }

        $lines = [];
        foreach ($history as $m) {
            if (!in_array($m->role, ['user','assistant'], true)) {
                continue;
            }
            $prefix = $m->role === 'user' ? '生徒: ' : '彩: ';
            $lines[] = $prefix.str_replace(["\r","\n"], ' ', (string) $m->content);
        }
        $txt = trim(implode(' / ', $lines));
        if (mb_strlen($txt, 'UTF-8') > 200) {
            $txt = mb_substr($txt, 0, 200, 'UTF-8').'…';
        }
        return $txt;
    }

    /** 現在の生徒を取得（ガード/クエリ） */
    private function currentStudent(Request $request): ?Student
    {
        if (auth('student')->check()) {
            return auth('student')->user();
        }
        if (auth()->check() && auth()->user() instanceof Student) {
            return auth()->user();
        }
        if ($id = (int) $request->query('student_id')) {
            return Student::find($id);
        }
        return null;
    }
}
