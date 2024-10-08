<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationBackground extends Model
{
    use HasFactory;
    public $table='education_background';
    public function users(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
