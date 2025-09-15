<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\CarbonImmutable;

class Holiday extends Model
{
    protected $table = 'holidays';

    // ★ これが無いと mass assign で date が落ちて、name が date にズレる事故の元
    protected $fillable = ['date', 'name', 'category'];

    // ★ 取得時は CarbonImmutable、保存時はミューテータで Y-m-d に揃える
    protected $casts = [
        'date' => 'immutable_date',
    ];

    // ★ 保存前に必ず Y-m-d へ正規化
    public function setDateAttribute($value): void
    {
        $this->attributes['date'] = CarbonImmutable::parse($value)->format('Y-m-d');
    }
}
