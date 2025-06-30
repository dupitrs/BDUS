<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apliecinajums extends Model
{
    public $timestamps = false;
    protected $table = 'apliecinajums';

    protected $fillable = [
        'izveides_datums',
        'administratora_epasts',
        'lietotaja_epasts',
    ];
}
