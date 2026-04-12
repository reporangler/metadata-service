<?php

namespace Tests\Unit\Policies;

use App\Policies\PackageGroupPolicy;
use PHPUnit\Framework\TestCase;

class PackageGroupPolicyTest extends TestCase
{
    private $policy;

    protected function setUp()
    {
        parent::setUp();
        $this->policy = new PackageGroupPolicy();
    }

    public function testCreateAllowedForAdmin()
    {
        $user = (object)['is_admin_user' => true];
        $this->assertTrue($this->policy->create($user));
    }

    public function testCreateDeniedForNonAdmin()
    {
        $user = (object)['is_admin_user' => false];
        $this->assertFalse($this->policy->create($user));
    }

    public function testUpdateAllowedForAdmin()
    {
        $user = (object)['is_admin_user' => true];
        $this->assertTrue($this->policy->update($user));
    }

    public function testUpdateDeniedForNonAdmin()
    {
        $user = (object)['is_admin_user' => false];
        $this->assertFalse($this->policy->update($user));
    }

    public function testRemoveAllowedForAdmin()
    {
        $user = (object)['is_admin_user' => true];
        $this->assertTrue($this->policy->remove($user));
    }

    public function testRemoveDeniedForNonAdmin()
    {
        $user = (object)['is_admin_user' => false];
        $this->assertFalse($this->policy->remove($user));
    }
}
