<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function __construct()
    {
        // 職員・管理者のみ（必要に応じてロール判定を調整）
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        $year     = (int)($request->query('year') ?: now()->year);
        $month    = (int)($request->query('month') ?: now()->month);
        $category = $request->query('category');

        $query = Holiday::query()->orderBy('date');
        if ($year)  $query->whereYear('date', $year);
        if ($month) $query->whereMonth('date', $month);
        if ($category) $query->where('category', $category);

        $holidays = $query->paginate(50)->withQueryString();

        return view('staff.holidays.index', [
            'holidays' => $holidays,
            'year'     => $year,
            'month'    => $month,
            'category' => $category,
        ]);
    }

    public function create()
    {
        return view('staff.holidays.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date'     => ['required','date','unique:holidays,date'],
            'name'     => ['required','string','max:255'],
            'category' => ['required','in:national,gw,obon,yearend,other'],
        ]);
        Holiday::create($data);
        return redirect()->route('staff.holidays.index')->with('status', '登録しました');
    }

    public function edit(Holiday $holiday)
    {
        return view('staff.holidays.edit', compact('holiday'));
    }

    public function update(Request $request, Holiday $holiday)
    {
        $data = $request->validate([
            'date'     => ['required','date','unique:holidays,date,'.$holiday->id],
            'name'     => ['required','string','max:255'],
            'category' => ['required','in:national,gw,obon,yearend,other'],
        ]);
        $holiday->update($data);
        return redirect()->route('staff.holidays.index')->with('status', '更新しました');
    }

    public function destroy(Holiday $holiday)
    {
        $holiday->delete();
        return back()->with('status', '削除しました');
    }

    /** 期間一括登録（GW/お盆/年末年始など） */
    public function bulkStore(Request $request)
    {
        $data = $request->validate([
            'start_date' => ['required','date'],
            'end_date'   => ['required','date','after_or_equal:start_date'],
            'name'       => ['required','string','max:255'],
            'category'   => ['required','in:national,gw,obon,yearend,other'],
        ]);

        $start = CarbonImmutable::parse($data['start_date']);
        $end   = CarbonImmutable::parse($data['end_date']);
        $count = 0;
        for ($d = $start; $d <= $end; $d = $d->addDay()) {
            Holiday::updateOrCreate(
                ['date' => $d->toDateString()],
                ['name' => $data['name'], 'category' => $data['category']]
            );
            $count++;
        }
        return back()->with('status', "{$count}件登録/更新しました");
    }
}