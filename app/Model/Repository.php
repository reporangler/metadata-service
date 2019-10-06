<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use RepoRangler\Entity\Repository as RepositoryEntity;

class Repository extends RepositoryEntity
{
    protected $table = 'repository';
}
