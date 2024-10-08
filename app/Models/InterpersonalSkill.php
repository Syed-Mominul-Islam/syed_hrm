<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterpersonalSkill extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'skill_name',
        'description',
        'is_delete'
    ];
    public function users()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
