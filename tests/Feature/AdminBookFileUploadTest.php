<?php

namespace Tests\Feature;

use App\Livewire\AdminBookManager;
use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class AdminBookFileUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_upload_a_book_file_when_creating_a_book(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create(['is_admin' => true]);

        Livewire::actingAs($admin)
            ->test(AdminBookManager::class)
            ->set('title', 'Sample Book')
            ->set('author', 'Sample Author')
            ->set('description', 'Sample description')
            ->set('price', 12.50)
            ->set('stock', 10)
            ->set('bookFile', UploadedFile::fake()->create('sample.pdf', 120, 'application/pdf'))
            ->call('createBook');

        $book = Book::query()->first();
        $this->assertNotNull($book);
        $this->assertNotNull($book->file_path);
        Storage::disk('public')->assertExists($book->file_path);
    }

    public function test_admin_can_replace_an_existing_book_file(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create(['is_admin' => true]);

        Storage::disk('public')->put('books/files/old.pdf', 'old');

        $book = Book::query()->create([
            'title' => 'Old Book',
            'slug' => 'old-book',
            'author' => 'Someone',
            'description' => null,
            'price' => 5,
            'image' => null,
            'file_path' => 'books/files/old.pdf',
            'stock' => 1,
            'category_id' => null,
        ]);

        Livewire::actingAs($admin)
            ->test(AdminBookManager::class)
            ->call('openEditModal', $book)
            ->set('bookFile', UploadedFile::fake()->create('new.docx', 50, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'))
            ->call('updateBook');

        $book->refresh();

        $this->assertNotEquals('books/files/old.pdf', $book->file_path);
        Storage::disk('public')->assertMissing('books/files/old.pdf');
        Storage::disk('public')->assertExists($book->file_path);
    }
}

