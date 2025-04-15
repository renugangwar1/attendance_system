<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'user_type',
        'check_in',
        'check_out',
        'captured_image',
    ];
    // Attendance.php (Model)
public function faculty()
{
    return $this->belongsTo(Faculty::class, 'user_id');
}

}