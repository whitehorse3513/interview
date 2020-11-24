<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'age', 'email', 'phone', 'date', 'image'];

    public function interviews()
    {
        return $this->hasMany('App\Models\Interview', 'id', 'applicant_id');
    }
    public $timestamps = TRUE;
}
