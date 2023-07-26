<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Pulau extends Model
{
    protected $table = 'pulau';

    public function tanaman()
    {
        return $this->hasMany(Tanaman::class, 'id_pulau');
    }
}