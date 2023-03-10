<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Response;

class Report extends Model
{
    use HasFactory;
    protected $fillable = [
        'nik',
        'nama',
        'no_telp',
        'pengaduan',
        'foto',
    ];

    public function response ()
    {
        //hashone : one to one
        // table yang berperan sebagai PK
        // nama fungsi == nama model FK
        return $this->hasOne
        (Response::class);
    }
}
