<!doctype html>
<html lang="ja">
<head>
    <meta charset="utf-8">
<style>
  @page { size: A4 portrait; margin: 12mm; }

  /* ===== 日本語フォント（すべて public/fonts 配置）===== */
  /* IPAexGothic */
  @font-face { font-family:'ipaexg'; src:url("{{ public_path('fonts/ipaexg.ttf') }}") format('truetype'); font-weight:400; font-style:normal; }
  @font-face { font-family:'ipaexg'; src:url("{{ public_path('fonts/ipaexg.ttf') }}") format('truetype'); font-weight:700; font-style:normal; } /* Boldも同ファイル */
  @font-face { font-family:'ipaexg'; src:url("{{ public_path('fonts/ipaexg.ttf') }}") format('truetype'); font-weight:400; font-style:italic; }
  @font-face { font-family:'ipaexg'; src:url("{{ public_path('fonts/ipaexg.ttf') }}") format('truetype'); font-weight:700; font-style:italic; }

  /* IPAゴシックP（プロポーショナル） */
  @font-face { font-family:'ipagp'; src:url("{{ public_path('fonts/ipagp.ttf') }}") format('truetype'); font-weight:400; font-style:normal; }
  @font-face { font-family:'ipagp'; src:url("{{ public_path('fonts/ipagp.ttf') }}") format('truetype'); font-weight:700; font-style:normal; }
  @font-face { font-family:'ipagp'; src:url("{{ public_path('fonts/ipagp.ttf') }}") format('truetype'); font-weight:400; font-style:italic; }
  @font-face { font-family:'ipagp'; src:url("{{ public_path('fonts/ipagp.ttf') }}") format('truetype'); font-weight:700; font-style:italic; }

  /* IPA明朝 */
  @font-face { font-family:'ipam'; src:url("{{ public_path('fonts/ipam.ttf') }}") format('truetype'); font-weight:400; font-style:normal; }
  @font-face { font-family:'ipam'; src:url("{{ public_path('fonts/ipam.ttf') }}") format('truetype'); font-weight:700; font-style:normal; }
  @font-face { font-family:'ipam'; src:url("{{ public_path('fonts/ipam.ttf') }}") format('truetype'); font-weight:400; font-style:italic; }
  @font-face { font-family:'ipam'; src:url("{{ public_path('fonts/ipam.ttf') }}") format('truetype'); font-weight:700; font-style:italic; }

  /* IPA明朝P（プロポーショナル） */
  @font-face { font-family:'ipamp'; src:url("{{ public_path('fonts/ipamp.ttf') }}") format('truetype'); font-weight:400; font-style:normal; }
  @font-face { font-family:'ipamp'; src:url("{{ public_path('fonts/ipamp.ttf') }}") format('truetype'); font-weight:700; font-style:normal; }
  @font-face { font-family:'ipamp'; src:url("{{ public_path('fonts/ipamp.ttf') }}") format('truetype'); font-weight:400; font-style:italic; }
  @font-face { font-family:'ipamp'; src:url("{{ public_path('fonts/ipamp.ttf') }}") format('truetype'); font-weight:700; font-style:italic; }

  /* ★選択フォント（コントローラから $font を渡す） */
  body { font-family: '{{ $font ?? 'ipaexg' }}', sans-serif; font-size:11px; color:#111; }

  /* レイアウト */
  .header { width:100%; border-collapse:collapse; margin-bottom:8px; }
  .header td { vertical-align:top; }
  .title { font-size:16px; font-weight:700; } /* 太字でも同フォントを使えるようになった */
  .meta  { font-size:11px; color:#333; text-align:right; }

  table.calendar { width:100%; border-collapse:collapse; table-layout:fixed; }
  .calendar th, .calendar td { border:1px solid #999; padding:4px; vertical-align:top; height:95px; }
  .calendar thead th { background:#f2f2f2; }
  .sun { color:#d00; } .sat { color:#06c; }
  .dim { background:#f7f7f7; color:#888; }
  .off { background:#e6e6e6; }
  .day { font-weight:700; }
  .note { font-size:10px; margin-top:2px; }

  /* 任意：セル内で局所的にフォント切替したい場合 */
  .font-ipagp { font-family:'ipagp', sans-serif; }
  .font-ipam  { font-family:'ipam',  serif; }
  .font-ipamp { font-family:'ipamp', serif; }
  .font-ipaexg{ font-family:'ipaexg', sans-serif; }
</style>

</head>
<body>

    <!-- ヘッダ -->
    <table class="header">
        <tr>
            <td class="title">{{ $year }}年 {{ $month }}月 月間カレンダー</td>
            <td class="meta">
                生徒：{{ $student->last_name }} {{ $student->first_name }}
                 ／ 学校：{{ $student->school?->name ?? '未設定' }}
                 ／ 学年：{{ $student->grade ?? '未設定' }}
            </td>
        </tr>
    </table>

    <!-- カレンダー -->
    <table class="calendar">
        <thead>
            <tr>
                <th class="sun">日</th>
                <th>月</th>
                <th>火</th>
                <th>水</th>
                <th>木</th>
                <th>金</th>
                <th class="sat">土</th>
            </tr>
        </thead>
        <tbody>
        @foreach($grid as $week)
            <tr>
                @foreach($week as $cell)
                    @php
                        // クラス判定（Blade側で完結させ、unexpected token を避ける）
                        $classes = [];
                        if(!$cell['in_month']) $classes[] = 'dim';
                        if($cell['is_off'])    $classes[] = 'off';
                        $classAttr = $classes ? ' class="'.implode(' ', $classes).'"' : '';
                    @endphp
                    <td{!! $classAttr !!}>
                        <div class="day">{{ $cell['date']->day }}</div>
                        @if($cell['holiday'])
                            <div class="note">★{{ $cell['holiday']['name'] }}</div>
                        @endif
                        @if($cell['student_off'])
                            <div class="note">休：{{ $cell['student_off']['reason'] ?? '個別休' }}</div>
                        @endif
                    </td>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>

    <p class="note" style="margin-top:8px;">
        ※ 日曜・祝日・ゴールデンウィーク・お盆・年末年始、及び個別休は灰色表示。該当日の名称や理由も表示します。
    </p>

</body>
</html>
