<?php

namespace Tests\Unit;

use App\Models\Document;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DocumentTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_document()
    {
        $user = User::factory()->create();
        $documentData = [
            'title' => 'Test Document',
            'content' => 'This is a test document content',
            'user_id' => $user->id,
        ];

        $document = Document::create($documentData);

        $this->assertInstanceOf(Document::class, $document);
        $this->assertDatabaseHas('documents', ['title' => 'Test Document']);
    }

    public function test_document_relationships()
    {
        $document = Document::factory()->create();

        $this->assertInstanceOf(User::class, $document->user);
    }

    public function test_document_scope()
    {
        $user = User::factory()->create();
        Document::factory()->count(3)->create(['user_id' => $user->id]);
        Document::factory()->count(2)->create();

        $userDocuments = Document::forUser($user->id)->get();

        $this->assertCount(3, $userDocuments);
    }
}