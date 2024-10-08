<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotableProject extends Model
{
    use HasFactory;
    public $table = 'notable_projects';
    protected $fillable = [
        'user_id',
        'notable_project_name',
        'notable_project_description',
        'is_delete',
    ];
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }
}
