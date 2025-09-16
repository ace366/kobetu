<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classroom extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code', 'name', 'postal_code', 'address', 'tel', 'email',
    ];
    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
