<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LinkClick extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'link_id',
        'ip',
        'city',
        'region',
        'country',
    ];

    public function link()
    {
        return $this->belongsTo(Link::class);
    }
}
