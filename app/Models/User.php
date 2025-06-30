<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'lietotajs';
    protected $primaryKey = 'lietotaja_epasts';  // svarīgi, ja nav id
    public $incrementing = false;                // ja primārā atslēga nav skaitlis
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'vards', 'uzvards', 'personas_kods', 'adrese', 'lietotaja_epasts', 'parole'
    ];

    protected $hidden = ['parole'];

    public function getAuthIdentifierName()
    {
        return 'lietotaja_epasts';
    }

    public function getAuthPassword()
    {
        return $this->parole;
    }
}


