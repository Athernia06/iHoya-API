<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{
    protected $table = 'forums'; // Nama tabel yang terkait dengan model ini

    protected $fillable = [
        'id_user', 
        'tanggal', 
        'deskripsi', 
        'foto', 
        'like_count', 
        'comment_count',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'id_forum');
    }

    public function bookmarks()
    {
        return $this->hasMany(ForumBookmark::class, 'id_forum');
    }
    public $timestamps = false;
}

class Like extends Model
{
    protected $fillable = [
        'id_forum',
        'id_user',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

class Comment extends Model
{
    protected $fillable = [
        'id_forum',
        'id_user',
        'deskripsi',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

class Share extends Model
{
    protected $fillable = [
        'id_forum',
        'id_user',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

class Bookmark extends Model
{
    protected $fillable = [
        'id_forum',
        'id_user',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}