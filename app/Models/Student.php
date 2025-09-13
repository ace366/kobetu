<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'last_name',
        'first_name',
        'last_name_kana',
        'first_name_kana',
        'school_id',
        'grade',
        'phone',
        'email',
        'password',
    ];

    // 学校リレーション
    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
