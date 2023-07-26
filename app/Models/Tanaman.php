<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Tanaman extends Model
{
    protected $table = 'tanaman';
    //protected $fillable = ['id_pulau', 'nama', 'deskripsi', 'foto'];

    public function pulau()
    {
        return $this->belongsTo(Pulau::class, 'id_pulau');
    }
    public $timestamps = false;
}