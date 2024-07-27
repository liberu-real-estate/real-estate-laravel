<?php

namespace Tests\Unit;

use App\Models\DocumentCategory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DocumentCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_document_category()
    {
        $categoryData = [
            'name' => 'Test Category',
            'description' => 'This is a test category',
        ];

        $category = DocumentCategory::create($categoryData);

        $this->assertInstanceOf(DocumentCategory::class, $category);
        $this->assertDatabaseHas('document_categories', $categoryData);
    }

    public function test_document_category_relationships()
    {
        $category = DocumentCategory::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $category->documents);
    }

    public function test_document_category_attributes()
    {
        $category = DocumentCategory::factory()->create([
            'name' => 'Test Category',
            'description' => 'This is a test category',
        ]);

        $this->assertEquals('Test Category', $category->name);
        $this->assertEquals('This is a test category', $category->description);
    }
}