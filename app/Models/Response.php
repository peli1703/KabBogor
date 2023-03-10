<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Report;

class Response extends Model
{
    use HasFactory;
    protected $fillable =[
        'report_id',
        'status',
        'pesan',
    ];

    public function report ()
    {
        //belongsto : disambungkan dengan table nama(PKnya ada dimaana)
        // table yang berperan sebagai FK
        // nama fungsi == nama model PK
        return $this->belongsTo
        (Report::class);
    }
}
