<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'forums'; // Nama tabel yang terkait dengan model ini

    protected $fillable = [
        'deskripsi',
        'foto',
        'id_user',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public $timestamps = false;
}
