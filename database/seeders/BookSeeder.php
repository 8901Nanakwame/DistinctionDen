<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create book categories if they don't exist
        $programmingCategory = Category::firstOrCreate(
            ['name' => 'Programming', 'slug' => 'programming'],
            ['type' => 'book']
        );

        $businessCategory = Category::firstOrCreate(
            ['name' => 'Business', 'slug' => 'business'],
            ['type' => 'book']
        );

        $designCategory = Category::firstOrCreate(
            ['name' => 'Design', 'slug' => 'design'],
            ['type' => 'book']
        );

        $scienceCategory = Category::firstOrCreate(
            ['name' => 'Science', 'slug' => 'science'],
            ['type' => 'book']
        );

        // Sample books
        $books = [
            [
                'title' => 'Clean Code: A Handbook of Agile Software Craftsmanship',
                'author' => 'Robert C. Martin',
                'description' => 'Even bad code can function. But if code isn\'t clean, it can bring a development organization to its knees. This book is a must for any developer who wants to produce high-quality code.',
                'price' => 299.99,
                'stock' => 25,
                'category_id' => $programmingCategory->id,
            ],
            [
                'title' => 'The Pragmatic Programmer: Your Journey to Mastery',
                'author' => 'Andrew Hunt, David Thomas',
                'description' => 'One of the most significant books in programming history, The Pragmatic Programmer is a comprehensive guide to software development best practices.',
                'price' => 349.99,
                'stock' => 18,
                'category_id' => $programmingCategory->id,
            ],
            [
                'title' => 'Design Patterns: Elements of Reusable Object-Oriented Software',
                'author' => 'Erich Gamma, Richard Helm, Ralph Johnson, John Vlissides',
                'description' => 'Capturing a wealth of experience about the design of object-oriented software, four top-notch designers present a catalog of solutions.',
                'price' => 399.99,
                'stock' => 15,
                'category_id' => $programmingCategory->id,
            ],
            [
                'title' => 'Zero to One: Notes on Startups',
                'author' => 'Peter Thiel, Blake Masters',
                'description' => 'The great secret of our time is that there are still uncharted frontiers to explore and new inventions to create. This book shows you how to find them.',
                'price' => 189.99,
                'stock' => 30,
                'category_id' => $businessCategory->id,
            ],
            [
                'title' => 'The Lean Startup',
                'author' => 'Eric Ries',
                'description' => 'Most startups fail. But many of those failures are preventable. The Lean Startup is a new approach being adopted across the globe.',
                'price' => 219.99,
                'stock' => 22,
                'category_id' => $businessCategory->id,
            ],
            [
                'title' => 'Don\'t Make Me Think: A Common Sense Approach to Web Usability',
                'author' => 'Steve Krug',
                'description' => 'Since Don\'t Make Me Think was first published in 2000, hundreds of thousands of Web designers and developers have relied on usability guru Steve Krug.',
                'price' => 259.99,
                'stock' => 20,
                'category_id' => $designCategory->id,
            ],
            [
                'title' => 'The Design of Everyday Things',
                'author' => 'Don Norman',
                'description' => 'Even the smartest among us can feel inept as we fail to figure out which light switch or oven burner to turn on, or whether to push, pull, or slide a door.',
                'price' => 279.99,
                'stock' => 17,
                'category_id' => $designCategory->id,
            ],
            [
                'title' => 'A Brief History of Time',
                'author' => 'Stephen Hawking',
                'description' => 'A landmark volume in science writing by one of the great minds of our time, Stephen Hawking\'s book explores the nature of time and the universe.',
                'price' => 169.99,
                'stock' => 35,
                'category_id' => $scienceCategory->id,
            ],
            [
                'title' => 'The Selfish Gene',
                'author' => 'Richard Dawkins',
                'description' => 'Richard Dawkins\'s classic work on evolutionary biology has been influential in shaping modern understanding of genetics and natural selection.',
                'price' => 199.99,
                'stock' => 28,
                'category_id' => $scienceCategory->id,
            ],
            [
                'title' => 'Introduction to Algorithms',
                'author' => 'Thomas H. Cormen, Charles E. Leiserson, Ronald L. Rivest, Clifford Stein',
                'description' => 'Some books on algorithms are rigorous but incomplete; this one covers a broad range of algorithms yet makes their design and analysis accessible.',
                'price' => 549.99,
                'stock' => 12,
                'category_id' => $programmingCategory->id,
            ],
            [
                'title' => 'You Don\'t Know JS: Scope & Closures',
                'author' => 'Kyle Simpson',
                'description' => 'No matter how much experience you have with JavaScript, odds are you don\'t fully understand the language. This concise guide dives into scope and closures.',
                'price' => 129.99,
                'stock' => 40,
                'category_id' => $programmingCategory->id,
            ],
            [
                'title' => 'Atomic Habits',
                'author' => 'James Clear',
                'description' => 'No matter your goals, Atomic Habits offers a proven framework for improving every day. This book reveals practical strategies for forming good habits.',
                'price' => 239.99,
                'stock' => 45,
                'category_id' => $businessCategory->id,
            ],
        ];

        foreach ($books as $book) {
            $book['slug'] = \Illuminate\Support\Str::slug($book['title']) . '-' . \Illuminate\Support\Str::random(5);
            Book::create($book);
        }
    }
}
