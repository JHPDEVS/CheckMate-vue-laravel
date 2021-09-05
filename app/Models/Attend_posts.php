<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attend_posts extends Model
{
    use HasFactory;

    public function imagePath() {
        $path = env('IMAGE_PATH', '/storage/images/');
        $imageFile = $this->image ?? 'noImage.png';

        return  $path . $imageFile;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Attend_comments::class);
    }
}