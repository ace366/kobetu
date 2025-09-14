<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Student;

class StudentRegisteredMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;

    /**
     * コンストラクタ
     */
    public function __construct(Student $student)
    {
        $this->student = $student;
    }

    /**
     * メールの組み立て
     */
    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject('【登録完了】生徒情報が登録されました') // ← UTF-8 そのまま
                    ->markdown('emails.students.registered');
    }
}
