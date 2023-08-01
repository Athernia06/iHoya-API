<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['id_forum', 'id_user', 'deskripsi', 'foto'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function forum()
    {
        return $this->belongsTo(Forum::class, 'id_forum');
    }
}