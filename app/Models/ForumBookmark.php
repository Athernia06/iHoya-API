<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class ForumBookmark extends Model
{
    protected $table = 'forumbookmarks'; // Nama tabel yang terkait dengan model ini
    protected $fillable = ['id_user', 'id_forum'];
    public $timestamps = false;

    public function forum()
    {
        return $this->belongsTo(Forum::class, 'id_forum');
    }
}