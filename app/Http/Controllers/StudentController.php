<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail; // ← これを忘れずに！
use App\Mail\StudentRegisteredMail;

class StudentController extends Controller
{
    /**
     * 生徒登録フォーム表示
     */
    public function create()
    {
        $schools = School::orderBy('prefecture')
                        ->orderBy('city')
                        ->orderBy('name')
                        ->get();

        return view('students.create', compact('schools'));
    }

    /**
     * 生徒登録処理
     */
    public function store(Request $request)
    {
        $request->validate([
            'last_name'       => 'required|string|max:50',
            'first_name'      => 'required|string|max:50',
            'last_name_kana'  => 'required|string|max:50',
            'first_name_kana' => 'required|string|max:50',
            'school_id'       => 'required|exists:schools,id',
            'classroom_id'    => 'required|exists:classrooms,id', // ★追加
            'grade'           => 'required|string',
            'phone'           => 'nullable|string|max:20',
            'email'           => 'required|email|unique:students,email',
            'password'        => 'required|string|min:4',
        ]);

        // DBに保存
        $student = Student::create([
            'last_name'       => $request->last_name,
            'first_name'      => $request->first_name,
            'last_name_kana'  => $request->last_name_kana,
            'first_name_kana' => $request->first_name_kana,
            'school_id'       => $request->school_id,
            'classroom_id'    => $request->classroom_id, // ★追加
            'grade'           => $request->grade,
            'phone'           => $request->phone,
            'email'           => $request->email,
            'password'        => Hash::make($request->password),
        ]);

        // 登録完了メール送信
        Mail::to($student->email)->send(new StudentRegisteredMail($student));

        return redirect()->route('welcome')
                         ->with('status', '生徒を登録しました。登録完了メールを送信しました。');
    }
}
