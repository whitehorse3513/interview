<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    use HasFactory;

    protected $fillable = ['applicant_id', 'question_id', 'rate', 'comment', 'file'];
    public $timestamps = TRUE;

    public function question()
    {
        return $this->belongsTo(Question::class,'question_id');
    }
}
