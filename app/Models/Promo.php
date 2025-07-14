<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $fillable = ['image', 'heading', 'subtext', 'button_text', 'button_link'];
}
