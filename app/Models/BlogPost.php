<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    protected $fillable = [
        'title',
        'author',
        'date',
        'content',
        'image',
        'status',
    ];
}
