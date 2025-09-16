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
<table class="header" style="width:100%; border-collapse:collapse; margin-bottom:8px; font-size:11px; border:1px solid #999; height:28px;">
    <tr>
       
        {{-- 左側：タイトル --}}
        <td colspan="9" style="background:#888; color:#fff; font-size:30px; font-weight:700; padding:6px 10px;">
            {{ $month }} 月分 カレンダー
        </td>

        {{-- 右側：チェック欄（白背景の表） --}}
        <td colspan="5" style="padding:0;">
            <table style="width:100%; border-collapse:collapse; font-size:11px; text-align:center;">
                <tr>
                    <td colspan="1" style="border:1px solid #999; padding:10px 4px; background:#fff;">通常</td>
                    <td colspan="2" style="border:1px solid #999; padding:10px 4px; background:#fff; border-right:none;">月</td>
                    <td colspan="1" style="border:1px solid #999; padding:10px 4px; background:#fff; border-left:none;">&nbsp;&nbsp;回</td>
                    <td colspan="1" style="border:1px solid #999; padding:10px 4px; background:#fff;">無料コマ</td>
                    <td colspan="1" style="border:1px solid #999; padding:10px 4px; background:#fff; border-right:none;"></td>
                    <td colspan="2" style="border:1px solid #999; padding:10px 4px; background:#fff; border-left:none;">&nbsp;&nbsp;回</td>
                </tr>
                <tr>
                    <td colspan="1" style="border:1px solid #999; padding:10px 4px; background:#fff;">スタ講残</td>
                    <td colspan="2" style="border:1px solid #999; padding:10px 4px; background:#fff; border-right:none;"></td>
                    <td colspan="1" style="border:1px solid #999; padding:10px 4px; background:#fff; border-left:none;">&nbsp;&nbsp;回</td>
                    <td colspan="1" style="border:1px solid #999; padding:10px 4px; background:#fff;">預かり授業残</td>
                    <td colspan="1" style="border:1px solid #999; padding:10px 4px; background:#fff; border-right:none;"></td>
                    <td colspan="2" style="border:1px solid #999; padding:10px 4px; background:#fff; border-left:none;">&nbsp;&nbsp;回</td>
                </tr>
                <tr>
                    <td colspan="1" style="border:1px solid #999; padding:10px 4px; background:#fff;">季節講習残</td>
                    <td colspan="2" style="border:1px solid #999; padding:10px 4px; background:#fff; border-right:none;"></td>
                    <td colspan="1" style="border:1px solid #999; padding:10px 4px; background:#fff;border-left:none;">&nbsp;&nbsp;回</td>
                    <td colspan="1" style="border:1px solid #999; padding:10px 4px; background:#fff;"></td>
                    <td colspan="1" style="border:1px solid #999; padding:10px 4px; background:#fff; border-right:none;">月</td>
                    <td colspan="2" style="border:1px solid #999; padding:10px 4px; background:#fff; border-left:none;">&nbsp;&nbsp;回</td>
                </tr>
            </table>
        </td>
    </tr>

{{-- 生徒情報 --}}
<tr style="background:#ddd; font-weight:700;">
    <td colspan="7" style="border:1px solid #999; padding:10px 4px;">生徒名</td>
    <td colspan="5" style="border:1px solid #999; padding:10px 4px;">学校</td>
    <td colspan="2" style="border:1px solid #999; padding:10px 4px;">学年</td>
</tr>
<tr>
    <td colspan="7" style="border:1px solid #999; padding:10px 6px;">
        {{ $student->last_name }} {{ $student->first_name }}
    </td>
    <td colspan="5" style="border:1px solid #999; padding:10px 6px;">
        {{ $student->school?->name ?? '未設定' }}
    </td>
    <td colspan="2" style="border:1px solid #999; padding:10px 6px;">
        {{ $student->grade ?? '未設定' }}
    </td>
</tr>

{{-- 講師情報 --}}
<tr style="background:#ddd; font-weight:700; text-align:center;">
    <td colspan="9" style="border:1px solid #999; text-align:center; vertical-align:middle; padding:10px 4px;">
        講師情報
    </td>
    <td colspan="2" style="border:1px solid #999; padding:10px 4px;">時間数</td>
    <td style="border:1px solid #999; padding:10px 4px;">前月末過不足</td>
    <td style="border:1px solid #999; padding:10px 4px;">今月規定回数</td>
    <td style="border:1px solid #999; padding:10px 4px;">今月実施予定</td>
</tr>

<tr>
    <td colspan="2" style="border:1px solid #999; padding:10px 10px;">講師名</td>
    <td colspan="4" style="border:1px solid #999; padding:10px 4px;"></td>
    <td colspan="3" style="border:1px solid #999; padding:10px 10px;">教科:</td>
    <td colspan="2" style="border:1px solid #999; text-align:center; padding:10px 4px;">
        1回&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;分
    </td>
    <td style="border:1px solid #999; text-align:right; padding:10px 4px;">回</td>
    <td style="border:1px solid #999; text-align:right; padding:10px 4px;">回</td>
    <td style="border:1px solid #999; text-align:right; padding:10px 4px;">回</td>
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
