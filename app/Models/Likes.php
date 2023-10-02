<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Likes extends Model
{
    protected $table = 'likes'; // Nama tabel yang terkait dengan model ini
    protected $fillable = ['id_user', 'id_forum'];
    public $timestamps = false;
    
    public function forum()
    {
        return $this->belongsTo(Forum::class, 'id_forum');
    }
}