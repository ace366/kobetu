<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentOffDay;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;

class StudentOffDayAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        $year      = (int)($request->query('year') ?: now()->year);
        $month     = (int)($request->query('month') ?: now()->month);
        $studentId = $request->query('student_id');

        $students = Student::with('school')
            ->orderBy('last_name_kana')
            ->orderBy('first_name_kana')
            ->get(['id','last_name','first_name','last_name_kana','first_name_kana','school_id']);

        $query = StudentOffDay::query()->with(['student.school'])->orderBy('date');
        if ($studentId) $query->where('student_id', $studentId);
        if ($year)      $query->whereYear('date', $year);
        if ($month)     $query->whereMonth('date', $month);

        $rows = $query->paginate(50)->withQueryString();

        return view('staff.student_off_days.index', [
            'rows'      => $rows,
            'students'  => $students,
            'studentId' => $studentId,
            'year'      => $year,
            'month'     => $month,
        ]);
    }

    public function create()
    {
        $students = Student::orderBy('last_name_kana')->orderBy('first_name_kana')->get(['id','last_name','first_name']);
        return view('staff.student_off_days.create', compact('students')); 
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => ['required','exists:students,id'],
            'date'       => ['required','date'],
            'reason'     => ['nullable','string','max:255'],
        ]);

        StudentOffDay::updateOrCreate(
            ['student_id' => $data['student_id'], 'date' => $data['date']],
            ['reason' => $data['reason']]
        );
        return redirect()->route('staff.student_off_days.index')->with('status', '登録しました');
    }

    public function edit(StudentOffDay $student_off_day)
    {
        $students = Student::orderBy('last_name_kana')->orderBy('first_name_kana')->get(['id','last_name','first_name']);
        return view('staff.student_off_days.edit', ['row' => $student_off_day, 'students' => $students]);
    }

    public function update(Request $request, StudentOffDay $student_off_day)
    {
        $data = $request->validate([
            'student_id' => ['required','exists:students,id'],
            'date'       => ['required','date'],
            'reason'     => ['nullable','string','max:255'],
        ]);

        // unique(student_id,date) を守るため、キー変更時は衝突チェック
        $exists = StudentOffDay::where('student_id', $data['student_id'])
            ->where('date', $data['date'])
            ->where('id', '!=', $student_off_day->id)
            ->exists();
        if ($exists) {
            return back()->withErrors(['date' => '同じ生徒・同じ日付の休みが既に存在します'])->withInput();
        }

        $student_off_day->update($data);
        return redirect()->route('staff.student_off_days.index')->with('status', '更新しました');
    }

    public function destroy(StudentOffDay $student_off_day)
    {
        $student_off_day->delete();
        return back()->with('status', '削除しました');
    }

    /** 期間一括登録（同一生徒に連続休を設定） */
    public function bulkStore(Request $request)
    {
        $data = $request->validate([
            'student_id' => ['required','exists:students,id'],
            'start_date' => ['required','date'],
            'end_date'   => ['required','date','after_or_equal:start_date'],
            'reason'     => ['nullable','string','max:255'],
        ]);

        $start = CarbonImmutable::parse($data['start_date']);
        $end   = CarbonImmutable::parse($data['end_date']);
        $count = 0;
        for ($d = $start; $d <= $end; $d = $d->addDay()) {
            StudentOffDay::updateOrCreate(
                ['student_id' => $data['student_id'], 'date' => $d->toDateString()],
                ['reason' => $data['reason']]
            );
            $count++;
        }
        return back()->with('status', "{$count}件登録/更新しました");
    }
}