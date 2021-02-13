<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brother extends Model
{
    use HasFactory;

    protected $table = 'brothers';
    protected $fillable = ['followed', 'followed_formated', 'username', 'profile_url'];

    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
