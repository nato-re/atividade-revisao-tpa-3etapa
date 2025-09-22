<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'year',
        'url_img',
    ];

    public function musics()
    {
        return $this->hasMany(Music::class);
    }
}
