<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $table = 'administrators';
    protected $primaryKey = 'administratora_epasts';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'administratora_epasts',
        'vards',
        'uzvards',
        'parole',
    ];

    protected $hidden = [
        'parole',
    ];

    public function getAuthIdentifierName()
    {
        return 'administratora_epasts';
    }

    public function getAuthPassword()
    {
        return $this->parole;
    }
}
