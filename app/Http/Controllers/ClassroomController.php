<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassroomRequest;
use App\Models\Classroom;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /** 管理権限(管理者/教師)のみ許可する簡易チェック */
    private function ensureStaff(): void
    {
        $role = auth()->user()->role ?? 'user';
        if (!in_array($role, ['admin','teacher'], true)) {
            abort(403, 'このページにアクセスする権限がありません。');
        }
    }

    public function index(Request $request)
    {
        $this->ensureStaff();

        $q = trim((string)$request->query('q', ''));
        $classrooms = Classroom::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function($qq) use ($q){
                    $qq->where('code','like',"%{$q}%")
                       ->orWhere('name','like',"%{$q}%")
                       ->orWhere('address','like',"%{$q}%")
                       ->orWhere('email','like',"%{$q}%");
                });
            })
            ->orderBy('code')
            ->paginate(20)
            ->withQueryString();

        return view('staff.classrooms.index', compact('classrooms','q'));
    }

    public function create()
    {
        $this->ensureStaff();
        $classroom = new Classroom();
        return view('staff.classrooms.create', compact('classroom'));
    }

    public function store(ClassroomRequest $request)
    {
        $this->ensureStaff();
        Classroom::create($request->validated());
        return redirect()->route('staff.classrooms.index')->with('status', '教室を登録しました。');
    }

    public function edit(Classroom $classroom)
    {
        $this->ensureStaff();
        return view('staff.classrooms.edit', compact('classroom'));
    }

    public function update(ClassroomRequest $request, Classroom $classroom)
    {
        $this->ensureStaff();
        $classroom->update($request->validated());
        return redirect()->route('staff.classrooms.index')->with('status', '教室を更新しました。');
    }

    public function destroy(Classroom $classroom)
    {
        $this->ensureStaff();
        $classroom->delete();
        return redirect()->route('staff.classrooms.index')->with('status', '教室を削除しました。');
    }
}
