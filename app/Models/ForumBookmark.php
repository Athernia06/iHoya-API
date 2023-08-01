<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class ForumBookmark extends Model
{
    protected $fillable = ['id_user', 'id_forum'];

    public function forum()
    {
        return $this->belongsTo(Forum::class, 'id_forum');
    }
}