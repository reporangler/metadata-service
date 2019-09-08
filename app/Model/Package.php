<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'definition' => 'array',
    ];
}