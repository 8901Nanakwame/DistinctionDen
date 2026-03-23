<?php

use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\Post;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This app is mostly page-based, with Livewire components handling the
| interactive parts of the UI. Most routes below render Blade views, and
| some use implicit route-model binding for `Exam`.
|
*/

Route::get('/exams-list', function (\Illuminate\Http\Request $request) {
    $search = $request->query('search');
    $categories = \App\Models\Category::where('type', 'exam')
        ->with(['exams' => function ($query) use ($search) {
            $query->where('is_active', true)
                  ->withCount('questions');

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }
        }])
        ->whereHas('exams', function ($query) use ($search) {
            $query->where('is_active', true);
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }
        })
        ->orderBy('name')
        ->get();

    return view('homeExams', compact('categories', 'search'));
})->name('exams-list');

Route::get('/books-list', function (\Illuminate\Http\Request $request) {
    $search = $request->query('search');
    $query = \App\Models\Book::query()->with('category')->where('stock', '>', 0);

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', '%' . $search . '%')
              ->orWhere('author', 'like', '%' . $search . '%');
        });
    }

    $books = $query->latest()->get();

    return view('homeBooks', compact('books', 'search'));
})->name('home.books');

Route::view('/', 'welcome')->name('home');

// Blog (public).
Route::get('blog', function () {
    $posts = Post::query()
        ->with(['category', 'user'])
        ->whereNotNull('published_at')
        ->latest('published_at')
        ->paginate(12);

    return view('blog.index', compact('posts'));
})->name('blog.index');

Route::get('blog/{post:slug}', function (Post $post) {
    abort_if($post->published_at === null, 404);

    $post->load(['category', 'user']);

    return view('blog.show', compact('post'));
})->name('blog.show');

// Authenticated area (Fortify / Livewire auth scaffolding).
Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // Bookshop
    Route::view('books', 'books.index')->name('books.index');

    // Book detail
    Route::get('books/{book}', function (\App\Models\Book $book) {
        $book->load('category');
        return view('books.show', compact('book'));
    })->name('books.show');

    // Shopping Cart
    Route::view('cart', 'cart.index')->name('cart.index');

    // Exam browsing for signed-in users.
     Route::view('exams', 'exams.index')->name('exams.index');

    // Exam detail page.
    // Eager-load related data to keep the Blade view simple and avoid N+1 queries.
    Route::get('exams/{exam}', function (Exam $exam) {
        $exam->load(['category', 'questions', 'attempts']);
        return view('exams.show', compact('exam'));
    })->name('exams.show');

    // Exam-taking page.
    // The Livewire component will handle the flow; we preload category/questions for display.
    Route::get('exams/{exam}/take', function (Exam $exam) {
        $exam->load(['category', 'questions']);
        return view('exams.take', compact('exam'));
    })->name('exams.take');

    // Exam correction/review page.
    Route::get('exams/{exam}/correction/{attempt}', function (Exam $exam, ExamAttempt $attempt) {
        $exam->load(['category', 'questions']);
        return view('exams.correction', compact('exam', 'attempt'));
    })->name('exams.correction');

    // Admin-only pages (see `App\Http\Middleware\AdminMiddleware`).
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        Route::view('questions', 'admin.questions')->name('admin.questions');
        Route::view('exams', 'admin.exams')->name('admin.exams');
        Route::view('categories', 'admin.categories')->name('admin.categories');
        Route::view('books', 'admin.books')->name('admin.books');
        Route::view('blogs', 'admin.blogs')->name('admin.blogs');
    });
});

// Settings routes provided by the starter kit (profile/security/appearance/2FA).
require __DIR__.'/settings.php';
