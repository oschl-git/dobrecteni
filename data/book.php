<?php
// This class is used for creating objects of books and managing them in the database.

require_once 'database_access.php';

class Book {
	public int $id;
	public string $name;
	public string $author;
	public string $genre;
	public bool $read;
	public ?int $rating;
	public ?string $isbn;
	public ?string $date_published;
	public ?int $pages;
	public ?string $notes;


	public function __construct(
		int $id, 
		string $name,
		string $author,
		string $genre,
		bool $read,
		?int $rating,
		?string $isbn,
		?string $date_published,
		?int $pages,
		?string $notes
	){
		$this->id = $id;
		$this->name = $name;
		$this->author = $author;
		$this->genre = $genre;
		$this->read = $read;
		$this->rating = $rating;
		$this->isbn = $isbn;
		$this->date_published = $date_published;
		$this->pages = $pages;
		$this->notes = $notes;
	}
}

// Returns an array of books from the database for the provided user ID.
function getArrayOfBooksForID(int $id): array {
	global $pdo;

	$query = 'SELECT * FROM books WHERE (id_user = :id)';
	$values = array(':id' => $id);
	
	try {
		$res = $pdo->prepare($query);
		$res->execute($values);
	}
	catch (PDOException $e) {
		throw new Exception('Database query error.');
	}
	
	$result = $res->fetchAll(\PDO::FETCH_ASSOC);

	$output = [];

	foreach ($result as $book) {
		array_push($output, new Book(
			$book['id'],
			$book['name'],
			$book['author'],
			$book['genre'],
			$book['read'],
			$book['rating'],
			$book['isbn'],
			$book['date_published'],
			$book['pages'],
			$book['notes'],
		));
	}

	return $output;
}

// Adds a book to the database.
function addBookToDatabase(
	int $user_id,
	string $name,
	string $author,
	string $genre,
	bool $read,
	?int $rating,
	?string $isbn,
	?string $date_published,
	?int $pages,
	?string $notes
): void {
	global $pdo;
	
	bookValidityChecks($name, $author, $genre, $isbn, $notes);
	
	$query = 'INSERT INTO books ' . 
             '(id_user, name, author, genre, `read`, rating, isbn, date_published, pages, notes) VALUES ' . 
			 '(:id_user, :name, :author, :genre, :read, :rating, :isbn, :date_published, :pages, :notes);';
	$values = array(
		':id_user' => $user_id,
		':name' => $name,
		':author' => $author,
		':genre' => $genre,
		':read' => $read,
		':rating' => isset($rating) ? $rating : null,
		':isbn' => isset($isbn) ? $isbn : null,
		':date_published' => isset($date_published) ? $date_published : null,
		':pages' => isset($pages) ? $pages : null,
		':notes' => isset($notes) ? $notes : null,
	);
	
	try {
		$res = $pdo->prepare($query);
		$res->execute($values);
	}
	catch (PDOException $e) {
		throw new Exception($e->getMessage());
	}
}

// Deletes a book from the database for the provided book ID. User ID required for verification.
function deleteBookFromDatabase(int $book_id, int $user_id): void {
	global $pdo;
	
	$query = 'DELETE FROM books WHERE (id = :book_id AND id_user = :user_id)';
	
	$values = array(':book_id' => $book_id, ':user_id' => $user_id);
	
	try {
		$res = $pdo->prepare($query);
		$res->execute($values);
	}
	catch (PDOException $e) {
	   throw new Exception('Database query error.');
	}
}

// Edits a book in the database. User ID required for verification.
function editBookInDatabase(
	int $id,
	int $user_id,
	string $name,
	string $author,
	string $genre,
	bool $read,
	?int $rating,
	?string $isbn,
	?string $date_published,
	?int $pages,
	?string $notes
): void {
	global $pdo;
	
	bookValidityChecks($name, $author, $genre, $isbn, $notes);
	
	$query = 'UPDATE books SET ' . 
             'name = :name, ' .
             'author = :author, ' .
             'genre = :genre, ' .
             '`read` = :read, ' .
             'rating = :rating, ' .
             'isbn = :isbn, ' .
             'date_published = :date_published, ' .
             'pages = :pages, ' .
             'notes = :notes ' .
			 'WHERE id = :id AND id_user = :id_user;';
	
	$values = array(
		':id' => $id,
		':id_user' => $user_id,
		':name' => $name,
		':author' => $author,
		':genre' => $genre,
		':read' => $read,
		':rating' => isset($rating) ? $rating : null,
		':isbn' => isset($isbn) ? $isbn : null,
		':date_published' => isset($date_published) ? $date_published : null,
		':pages' => isset($pages) ? $pages : null,
		':notes' => isset($notes) ? $notes : null,
	);
	
	try {
		$res = $pdo->prepare($query);
		$res->execute($values);
	}
	catch (PDOException $e) {
		throw new Exception($e->getMessage());
	}
}

// Checks validity of the provided items.
function bookValidityChecks(
	string $name,
	string $author,
	string $genre,
	?string $isbn,
	?string $notes
): void {
	
	// Book name:
	$lengthCheck = hasLengthInInterval($name, 1, 254);
	if ($lengthCheck != 0) match ($lengthCheck) {
		-1 => throw new Exception('Name of the book is too short.'),
		1 => throw new Exception('Name of the book is too long.'),
	};

	// Author name:
	$lengthCheck = hasLengthInInterval($author, 1, 254);
	if ($lengthCheck != 0) match ($lengthCheck) {
		-1 => throw new Exception('Author name is too short.'),
		1 => throw new Exception('Author name is too long.'),
	};

	// Genre:
	$lengthCheck = hasLengthInInterval($genre, 1, 254);
	if ($lengthCheck != 0) match ($lengthCheck) {
		-1 => throw new Exception('Name of the genre is too short.'),
		1 => throw new Exception('Name of the genre is too long.'),
	};

	// ISBN:
	if (isset($isbn)) {
		$isbnLength = mb_strlen($isbn);
		if ($isbnLength != 10 && $isbnLength != 13) throw new Exception('ISBN must be 10 or 13 characters long.');
	}
	
	// Notes:
	if (isset($notes)) {
		$lengthCheck = hasLengthInInterval($notes, 0, 2000);
		if ($lengthCheck != 0) match ($lengthCheck) {
			-1 => throw new Exception('Notes are short.'),
			1 => throw new Exception('Notes are too long (2000 character limit).'),
		};
	}
}

// Checks if provided string is at least as long than min_length and at least as short as 
// max_length. Returns 0 if yes, -1 if shorter, +1 if longer.
function hasLengthInInterval(string $text, int $min_length, int $max_length): int {
	$length = mb_strlen($text);
	if ($length < $min_length) return -1;
	if ($length > $max_length) return 1;
	return 0;
}

?>