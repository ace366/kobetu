<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function create()
    {
        $schools = School::orderBy('prefecture')->orderBy('city')->orderBy('name')->get();
        return view('students.create', compact('schools'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'last_name'      => 'required|string|max:50',
            'first_name'     => 'required|string|max:50',
            'last_name_kana' => 'required|string|max:50',
            'first_name_kana'=> 'required|string|max:50',
            'school_id'      => 'required|exists:schools,id', // 外部キーとしてバリデーション
            'grade'          => 'required|string',
            'phone'          => 'nullable|string|max:20',
            'email'          => 'required|email|unique:students,email',
            'password'       => 'required|string|min:4',
        ]);

        Student::create([
            'last_name'       => $request->last_name,
            'first_name'      => $request->first_name,
            'last_name_kana'  => $request->last_name_kana,
            'first_name_kana' => $request->first_name_kana,
            'school_id'       => $request->school_id,
            'grade'           => $request->grade,
            'phone'           => $request->phone,
            'email'           => $request->email,
            'password'        => Hash::make($request->password),
        ]);

        return redirect()->route('students.create')->with('status', '生徒を登録しました');
    }
}
