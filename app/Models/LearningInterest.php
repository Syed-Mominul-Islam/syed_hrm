<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningInterest extends Model
{
    use HasFactory;
    public $table = 'learning_interests';
    protected $fillable = [
        'user_id',
        'interest',
        'completed_course',
        'is_delete'
    ];
    public function users(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
