<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama',
        'gambar',
        'email'
    ];

    public $timestamps = false;
}
