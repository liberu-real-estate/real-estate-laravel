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

    public function test_update_branch()
    {
        $branch = Branch::factory()->create();
        $newData = [
            'name' => 'Updated Branch',
            'address' => '456 New St',
            'phone' => '9876543210',
            'email' => 'updated@branch.com',
        ];

        $branch->update($newData);

        $this->assertDatabaseHas('branches', $newData);
    }

    public function test_delete_branch()
    {
        $branch = Branch::factory()->create();
        $branchId = $branch->id;

        $branch->delete();

        $this->assertDatabaseMissing('branches', ['id' => $branchId]);
    }
}