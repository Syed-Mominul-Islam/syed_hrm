<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalInformation extends Model
{
    use HasFactory;
    public $table = 'additional_information';
    protected $fillable = [
        'user_id',
        'languages_known',
        'hobbies',
        'volunteer_work',
        'is_delete'

    ];

    // Define the relationship with the User model
    public function users()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
