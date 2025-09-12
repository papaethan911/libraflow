<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Author;
use App\Models\Category;

class RealBooksSeeder extends Seeder
{
	public function run(): void
	{
		$books = [
			['title' => 'The Hobbit', 'author' => 'J.R.R. Tolkien', 'genre' => 'Fantasy'],
			['title' => '1984', 'author' => 'George Orwell', 'genre' => 'Dystopian'],
			['title' => 'To Kill a Mockingbird', 'author' => 'Harper Lee', 'genre' => 'Classic'],
			['title' => 'The Girl with the Dragon Tattoo', 'author' => 'Stieg Larsson', 'genre' => 'Mystery'],
			['title' => 'The Shining', 'author' => 'Stephen King', 'genre' => 'Horror'],
			['title' => 'Pride and Prejudice', 'author' => 'Jane Austen', 'genre' => 'Romance'],
			['title' => 'Sapiens', 'author' => 'Yuval Noah Harari', 'genre' => 'Non-Fiction'],
			['title' => 'The Martian', 'author' => 'Andy Weir', 'genre' => 'Science Fiction'],
			['title' => 'Educated', 'author' => 'Tara Westover', 'genre' => 'Memoir'],
			['title' => 'The Name of the Wind', 'author' => 'Patrick Rothfuss', 'genre' => 'Fantasy'],
			['title' => 'Gone Girl', 'author' => 'Gillian Flynn', 'genre' => 'Thriller'],
			['title' => 'The Da Vinci Code', 'author' => 'Dan Brown', 'genre' => 'Thriller'],
			['title' => 'The Kite Runner', 'author' => 'Khaled Hosseini', 'genre' => 'Literary Fiction'],
			['title' => 'The Road', 'author' => 'Cormac McCarthy', 'genre' => 'Post-Apocalyptic'],
			['title' => 'The Night Circus', 'author' => 'Erin Morgenstern', 'genre' => 'Fantasy'],
			['title' => 'The Fault in Our Stars', 'author' => 'John Green', 'genre' => 'Young Adult'],
			['title' => 'The Book Thief', 'author' => 'Markus Zusak', 'genre' => 'Historical Fiction'],
			['title' => 'The Immortal Life of Henrietta Lacks', 'author' => 'Rebecca Skloot', 'genre' => 'Biography'],
			['title' => 'Atomic Habits', 'author' => 'James Clear', 'genre' => 'Self-Help'],
			['title' => 'The Shadow of the Wind', 'author' => 'Carlos Ruiz Zafón', 'genre' => 'Mystery'],
		];

		foreach ($books as $b) {
			$category = Category::firstOrCreate(['name' => $b['genre']]);
			$author = Author::firstOrCreate(['name' => $b['author']]);
			Book::firstOrCreate(
				['title' => $b['title'], 'author_id' => $author->id],
				[
					'category_id' => $category->id,
					'genre' => $b['genre'],
					'status' => 'available',
					'description' => null,
				]
			);
		}
	}
} 