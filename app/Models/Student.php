<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'last_name',
        'first_name',
        'last_name_kana',
        'first_name_kana',
        'school_id',
        'classroom_id',   // ← 追加
        'grade',
        'phone',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Laravel 10+: 自動ハッシュ
    protected $casts = [
        'password' => 'hashed',
    ];

    /** 学校リレーション: students.school_id -> schools.id */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id');
    }
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}
