<?php
namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Models\Student;
use App\Models\StudentOffDay;
use Barryvdh\DomPDF\Facade\Pdf; // barryvdh/laravel-dompdf を使用
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class StudentCalendarController extends Controller
{
    // 職員のみ閲覧できる想定
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /** 月選択＆プレビュー */
    public function index(Request $request)
    {
        $year  = (int) ($request->query('year') ?: now()->year);
        $month = (int) ($request->query('month') ?: now()->month);
        $studentId = $request->query('student_id');

        $students = Student::orderBy('last_name_kana')
            ->orderBy('first_name_kana')
            ->get(['id','last_name','first_name','school','grade']);

        $student = $studentId ? Student::find($studentId) : null;
        $grid = $student ? $this->buildMonthGrid($year, $month, $student->id) : [];

        return view('calendars.student_month', compact('students','student','year','month','grid'));
    }

    /** PDF 出力（1名分） */
    public function pdf(Request $request)
    {
        $data = $request->validate([
            'student_id' => ['required','integer','exists:students,id'],
            'year'       => ['required','integer','min:2000','max:2100'],
            'month'      => ['required','integer','min:1','max:12'],
        ]);
        $student = Student::findOrFail($data['student_id']);
        $grid    = $this->buildMonthGrid($data['year'], $data['month'], $student->id);

        $pdf = Pdf::loadView('calendars.student_month_pdf', [
            'student' => $student,
            'year'    => (int)$data['year'],
            'month'   => (int)$data['month'],
            'grid'    => $grid,
        ])->setPaper('A4','portrait');

        $filename = sprintf('calendar_%s_%04d-%02d.pdf', $student->id, $data['year'], $data['month']);
        return $pdf->download($filename);
    }

    /** 月の 7x6 グリッドを返す */
    private function buildMonthGrid(int $year, int $month, int $studentId): array
    {
        $first = CarbonImmutable::create($year, $month, 1)->startOfDay();
        $last  = $first->endOfMonth();

        // その月の祝日＆特別休暇（GW/お盆/年末年始）
        $monthHolidays = Holiday::whereBetween('date', [$first->toDateString(), $last->toDateString()])->get();
        $holidayByDate = $monthHolidays->keyBy(fn($h) => $h->date->toDateString());

        // 生徒の個別お休み
        $offDays = StudentOffDay::where('student_id', $studentId)
            ->whereBetween('date', [$first->toDateString(), $last->toDateString()])
            ->get()
            ->keyBy(fn($o) => $o->date->toDateString());

        // カレンダー開始（週は日曜始まり）
        $start = $first->startOfWeek(Carbon::SUNDAY);
        $end   = $last->endOfWeek(Carbon::SATURDAY);

        $cursor = $start;
        $weeks = [];
        while ($cursor <= $end) {
            $week = [];
            for ($i=0;$i<7;$i++) {
                $dateStr = $cursor->toDateString();
                $isCurrentMonth = ($cursor->month === $month);
                $isSunday = ($cursor->dayOfWeek === Carbon::SUNDAY);
                $holiday = $holidayByDate[$dateStr] ?? null;
                $studentOff = $offDays[$dateStr] ?? null;
                $isOff = $isSunday || $holiday || $studentOff;

                $week[] = [
                    'date' => $cursor,
                    'in_month' => $isCurrentMonth,
                    'is_sunday' => $isSunday,
                    'holiday' => $holiday ? ['name'=>$holiday->name,'category'=>$holiday->category] : null,
                    'student_off' => $studentOff ? ['reason'=>$studentOff->reason] : null,
                    'is_off' => $isOff,
                ];
                $cursor = $cursor->addDay();
            }
            $weeks[] = $week;
        }
        return $weeks; // [ [ {date..}, x7 ], x6 ]
    }
}