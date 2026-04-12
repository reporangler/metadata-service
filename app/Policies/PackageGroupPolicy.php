<?php
namespace App\Policies;

class PackageGroupPolicy
{
    public function create($user): bool
    {
        return $user->is_admin_user === true;
    }

    public function update($user): bool
    {
        return $user->is_admin_user === true;
    }

    public function remove($user): bool
    {
        return $user->is_admin_user === true;
    }
}
