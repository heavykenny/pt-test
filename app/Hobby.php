<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hobby extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'content', 'user_id',
    ];
}
