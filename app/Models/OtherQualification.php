<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherQualification extends Model
{
    use HasFactory;
    public $table='other_qualification';
    protected $fillable = [
        'user_id',
        'qualification_name',
        'passing_year',
    ];
    public function users(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
