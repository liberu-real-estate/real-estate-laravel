<?php

namespace Tests\Unit;

use App\Models\Branch;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BranchTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_branch()
    {
        $branchData = [
            'name' => 'Test Branch',
            'address' => '123 Test St',
            'phone' => '1234567890',
            'email' => 'test@branch.com',
        ];

        $branch = Branch::create($branchData);

        $this->assertInstanceOf(Branch::class, $branch);
        $this->assertDatabaseHas('branches', $branchData);
    }

    public function test_branch_relationships()
    {
        $branch = Branch::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $branch->users);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $branch->properties);
    }
}