<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Sludinajums extends Model
{
    // Norāda tabulas nosaukumu, ja tas nav "sludinajums**s**"
    protected $primaryKey = 'ID'; // lielajiem burtiem, ja tā ir DB
    public $incrementing = true;
    public $timestamps = true;

    protected $table = 'sludinajums';

    // Lauki, kurus drīkst masveidā aizpildīt
    protected $fillable = [
        'nosaukums',
        'apraksts',
        'norises_datums',
        'bilde',
        'visible',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
    ];
    
    // Aizvieto "visible" ar "is_visible" mutatoru
    public function getVisibleAttribute()
    {
        return $this->attributes['is_visible'];
    }
    
    public function setVisibleAttribute($value)
    {
        $this->attributes['is_visible'] = $value;
    }

    public function pieteikumi()
    {
        return $this->hasMany(Pieteikums::class, 'ID');
    }

    
    

    
    
}
