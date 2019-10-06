<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

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
        return $this->belongsTo(Repository::class);
    }

    static public function whereRepository(string $name)
    {
        return Package::whereHas('repository', function (Builder $query) use ($name) {
            $query->where('name', $name);
        });
    }
}
