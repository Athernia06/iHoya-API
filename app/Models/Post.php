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

    public function shares()
    {
        return $this->hasMany(Share::class);
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function getLikeCountAttribute()
    {
        return $this->likes->count();
    }

    public function getCommentCountAttribute()
    {
        return $this->comments->count();
    }
    public $timestamps = false;
}

class Like extends Model
{
    protected $fillable = [
        'post_id',
        'user_id',
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
        'post_id',
        'user_id',
        'text',
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
        'post_id',
        'user_id',
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
        'post_id',
        'user_id',
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