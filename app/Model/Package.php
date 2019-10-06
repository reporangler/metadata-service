<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use RepoRangler\Entity\Repository;

class Package extends Model
{
    protected $fillable = ['name', 'version', 'repository_id', 'package_group', 'definition'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'definition' => 'array',
    ];

    public function repository()
    {
        return $this->hasOne(Repository::class);
    }
}
