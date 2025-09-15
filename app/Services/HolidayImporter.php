<?php

namespace App\Services;

use App\Models\Holiday;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Http;

class HolidayImporter
{
    public function importYear(int $year, bool $overwrite = true, int $maxLine = 800): array
    {
        $created = $updated = $skipped = $errors = 0;
        $log = [];

        $base = 'http://api.excelapi.org/datetime';
        $ua   = 'HolidayImporter/1.2 (+Laravel)';

        for ($line = 1; $line <= $maxLine; $line++) {
            try {
                // 1) 1行ずつ取得
                $res = Http::timeout(10)
                    ->withHeaders(['User-Agent' => $ua, 'Accept' => 'application/json,text/plain;q=0.8,*/*;q=0.5'])
                    ->get($base . '/holiday-list', ['year' => $year, 'line' => $line]);

                if ($res->status() === 404) break;
                if (!$res->ok()) { $errors++; $log[] = "[line:$line] HTTP ".$res->status()." from holiday-list"; continue; }

                $raw = trim((string)$res->body());
                if ($this->isEmptyLike($raw)) break;

                // 値パース
                $date = null; $name = null;

                $json = json_decode($raw, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    if (is_string($json)) {
                        $date = $this->parseDateFromAny($json) ?: null;
                        if (!$date) $name = trim($json);
                    } elseif (is_array($json)) {
                        $date = $this->parseDateFromAny((string)($json['date'] ?? $json['ymd'] ?? ''));
                        $name = isset($json['name']) ? trim((string)$json['name'])
                              : (isset($json['title']) ? trim((string)$json['title']) : null);
                    }
                }

                if (!$date && str_contains($raw, ',')) {
                    [$d, $n] = array_pad(explode(',', $raw, 2), 2, null);
                    $date = $this->parseDateFromAny((string)$d);
                    if ($n !== null) $name = $name ?: trim((string)$n);
                }

                if (!$date) $date = $this->parseDateFromAny($raw);

                if (!$date) { $skipped++; $log[] = "[line:$line] cannot parse date from: ".$raw; continue; }
                if ((int)substr($date, 0, 4) !== $year) { $skipped++; $log[] = "[line:$line] different year: ".$date; continue; }

                // 2) 名称が未取得なら /holiday?date=YYYY/MM/DD で補完（★ここをスラッシュ固定に）
                if (!$name) {
                    try {
                        $dateSlash = $this->toSlashDate($date); // 'YYYY/MM/DD'
                        $res2 = Http::timeout(10)
                            ->withHeaders(['User-Agent' => $ua, 'Accept' => 'application/json,text/plain;q=0.8,*/*;q=0.5'])
                            ->get($base . '/holiday', ['date' => $dateSlash]);

                        if ($res2->ok()) {
                            $name = $this->extractName((string)$res2->body()) ?: $name;
                        }
                    } catch (\Throwable $e2) {
                        // 続行
                    }
                }

                // 3) カテゴリ判定
                $category = 'national';
                if ($name && (mb_strpos($name, '振替', 0, 'UTF-8') !== false)) {
                    $category = 'substitute';
                }

                // 4) 保存（冪等）
                $found = Holiday::where('date', $date)->first(); // whereDateは使わない
                if (!$found) {
                    Holiday::create([
                        'date'     => $date,              // DBは Y-m-d のまま
                        'name'     => $name ?: '祝日',
                        'category' => $category,
                    ]);
                    $created++;
                } else {
                    $newName = $name ?: $found->name;
                    $newCat  = $category ?: $found->category;
                    if ($overwrite && ($newName !== $found->name || $newCat !== $found->category)) {
                        $found->update(['name' => $newName, 'category' => $newCat]);
                        $updated++;
                    } else {
                        $skipped++;
                    }
                }
            } catch (\Throwable $e) {
                $errors++; $log[] = "[line:$line] exception: ".$e->getMessage();
            }
        }

        return compact('created','updated','skipped','errors','log');
    }

    private function isEmptyLike(?string $raw): bool
    {
        $r = mb_strtolower(trim((string)$raw));
        return $r === '' || $r === 'null' || $r === 'false' || $r === '0';
    }

    /** ほぼ何でも受ける → Y-m-d へ正規化 */
    private function parseDateFromAny(?string $s): ?string
    {
        if ($s === null) return null;
        $s = trim($s, " \t\n\r\0\x0B\"'");

        if (preg_match('/^(?<y>\d{4})\D?(?<m>\d{1,2})\D?(?<d>\d{1,2})$/', $s, $m)) {
            $y = (int)$m['y']; $mo = (int)$m['m']; $d = (int)$m['d'];
            if (checkdate($mo, $d, $y)) return sprintf('%04d-%02d-%02d', $y, $mo, $d);
        }
        try { return CarbonImmutable::parse($s)->toDateString(); }
        catch (\Throwable $e) { return null; }
    }

    /** API用：Y-m-d -> Y/m/d */
    private function toSlashDate(string $ymd): string
    {
        // 入力は Y-m-d 前提。保険でパースしてから整形。
        try {
            return CarbonImmutable::parse($ymd)->format('Y/m/d');
        } catch (\Throwable $e) {
            // 形式不定でも、非数字以外で分割して再組立て
            if (preg_match('/^(?<y>\d{4})\D(?<m>\d{1,2})\D(?<d>\d{1,2})$/', $ymd, $m)) {
                return sprintf('%04d/%02d/%02d', $m['y'], $m['m'], $m['d']);
            }
            // 最後の手段：そのまま返す
            return str_replace('-', '/', $ymd);
        }
    }

    /** 名称抽出（JSON/CSV/テキスト） */
    private function extractName(string $raw): ?string
    {
        $raw = trim($raw);
        if ($this->isEmptyLike($raw)) return null;

        $json = json_decode($raw, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            if (is_string($json)) return trim($json);
            if (is_array($json))  return trim((string)($json['name'] ?? $json['title'] ?? ''));
        }
        if (str_contains($raw, ',')) {
            [, $n] = array_pad(explode(',', $raw, 2), 2, null);
            return $n ? trim($n) : null;
        }
        return $raw;
    }
}
