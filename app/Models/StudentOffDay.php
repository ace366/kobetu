<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentOffDay extends Model
{
    protected $fillable = ['student_id','date','reason'];
    protected $casts = [ 'date' => 'date' ];
}