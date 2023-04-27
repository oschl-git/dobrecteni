<?php
// This class is used for creating objects of books.

require_once 'database_access.php';

class Book {
	public int $id;
	public string $name;
	public string $author;
	public string $genre;
	public string $isbn;
	public string $date_published;
	public int $pages;
	public string $notes;


	public function __construct(
		int $id, 
		string $name,
		string $author,
		string $genre,
		string $isbn,
		string $date_published,
		int $pages,
		string $notes
	){
		$this->id = $id;
		$this->name = $name;
		$this->author = $author;
		$this->genre = $genre;
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
			$book['isbn'],
			$book['date_published'],
			$book['pages'],
			$book['notes'],
		));
	}

	return $output;
}

?>