<?php
namespace App\Policies;

class PackageGroupPolicy
{
    public function create($user): bool
    {
        error_log(__METHOD__);
        return true;
    }

    public function update($user): bool
    {
        error_log(__METHOD__);
        return true;
    }

    public function remove($user): bool
    {
        error_log(__METHOD__);
        return true;
    }
}
