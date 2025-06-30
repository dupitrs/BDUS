<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pieteikums extends Model
{
    protected $table = 'pieteikums';
    protected $fillable = ['lietotaja_epasts', 'ID', 'statuss'];
    public $timestamps = false;

    public function sludinajums()
    {
        return $this->belongsTo(Sludinajums::class, 'ID');
    }
}
