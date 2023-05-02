<?php
// This class is used for creating objects of books.

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
) {
	global $pdo;
	
	//TODO: checks
	
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

function deleteBookFromDatabase(int $book_id, int $user_id) {
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
) {
	global $pdo;
	
	//TODO: checks
	
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

?>