<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfessionalSkill extends Model
{
    use HasFactory;
    protected $table = 'professional_skills';
    // Add the fields that can be mass-assigned
    protected $fillable = [
        'user_id',
        'skill_name',
        'description',
        'is_delete'
    ];
    public function users(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
