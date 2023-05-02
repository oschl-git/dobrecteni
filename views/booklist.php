<?php
	require_once '../authentication/user.php';
	require_once '../data/database_access.php';
	require_once '../data/book.php';

	session_start();

	if (!isset($_SESSION['user'])) {
		header('Location: ./login.php');
		exit();
	}
	
	if (isset($_POST['delete-book'])) {
		deleteBookFromDatabase($_POST['delete-book-id'], $_SESSION['user']->getId());
	}

	if (isset($_POST['add-book'])) {
		addBookToDatabase(
			$_SESSION['user']->getId(),
			$_POST['add-name'],
			$_POST['add-author'],
			$_POST['add-genre'],
			isset($_POST['add-read']) ? true : false,
			$_POST['add-rating'] != '' ? (int)$_POST['add-rating'] : null,
			$_POST['add-isbn'] != '' ? $_POST['add-isbn'] : null,
			$_POST['add-date'] != '' ? $_POST['add-date'] : null,
			$_POST['add-pages'] != '' ? (int)$_POST['add-pages'] : null,
			$_POST['add-notes'] != '' ? $_POST['add-notes'] : null,
		);
	}

	if (isset($_POST['edit-book'])) {
		editBookInDatabase(
			$_POST['edit-book-id'],
			$_SESSION['user']->getId(),
			$_POST['edit-name'],
			$_POST['edit-author'],
			$_POST['edit-genre'],
			isset($_POST['edit-read']) ? true : false,
			$_POST['edit-rating'] != '' ? (int)$_POST['edit-rating'] : null,
			$_POST['edit-isbn'] != '' ? $_POST['edit-isbn'] : null,
			$_POST['edit-date'] != '' ? $_POST['edit-date'] : null,
			$_POST['edit-pages'] != '' ? (int)$_POST['edit-pages'] : null,
			$_POST['edit-notes'] != '' ? $_POST['edit-notes'] : null,
		);
	}

	if (isset($_POST['logout'])) {
		$_SESSION['user'] = null;
		header('Location: ./login.php');
		exit();
	}
?>

<!DOCTYPE html>
<html lang="cs">
	<head>
		<link rel="stylesheet" href="../css/booklist.css">
		<link rel="stylesheet" href="../css/global.css">
	</head>
	<body>
		<form method="POST" name="logout" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			<button type="sumbit" name="logout">Logout</button>
		</form>

		<h1><?php echo htmlspecialchars($_SESSION['user']->getUsername()); ?>'s books:</h1>

		<table id="book-table">
			<tr>
				<th>Name</th>
				<th>Author</th>
				<th>Genre</th>
				<th>Rating</th>
				<th>Read</th>
				<th>Actions</th>
			</tr>
		</table>

		<button type="button" onclick="addBook(this)">+ Add book</button>

		<div id="details-container" style="display: none">
			<h1>Book details</h1>
			<p>Name: <span id="details-name"></span></p>
			<p>Author: <span id="details-author"></span></p>
			<p>Genre: <span id="details-genre"></span></p>
			<p>Read: <span id="details-read"></span></p>
			<p>Rating: <span id="details-rating"></span></p>
			<p>ISBN: <span id="details-isbn"></span></p>
			<p>Date published: <span id="details-date"></span></p>
			<p>Pages: <span id="details-pages"></span></p>
			<p>Notes: <span id="details-notes"></span></p>
			<img id="details-cover" src="" alt="Cover image">
			<button type="button" onclick="hideAllContainers()">Close</button>
		</div>

		<div id="edit-container" style="display: none">
			<h1>Book editing</h1>
			<form method="POST" name="edit-book" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
				<div>
					<label for="edit-name">Name*: </label>
					<input type="text" name="edit-name" id="edit-name" required>
				</div>
				<div>
					<label for="edit-author">Author*: </label>
					<input type="text" name="edit-author" id="edit-author" required>
				</div>
				<div>
					<label for="edit-genre">Genre*: </label>
					<input type="text" name="edit-genre" id="edit-genre" required>
				</div>
				<div>
					<label for="edit-read">Read*: </label>
					<input type="checkbox" name="edit-read" id="edit-read">
				</div>
				<div>
					<label for="edit-rating">Rating: </label>
					<select name="edit-rating" id="edit-rating">
						<option value="">No rating</option>
						<option value="0">0 ★</option>
						<option value="1">0.5 ★</option>
						<option value="2">1 ★</option>
						<option value="3">1.5 ★</option>
						<option value="4">2 ★</option>
						<option value="5">2.5 ★</option>
						<option value="6">3 ★</option>
						<option value="7">3.5 ★</option>
						<option value="8">4 ★</option>
						<option value="9">4.5 ★</option>
						<option value="10">5 ★</option>
					</select>
				</div>
				<div>
					<label for="edit-isbn">ISBN (used for covers): </label>
					<input type="text" name="edit-isbn" id="edit-isbn">
				</div>
				<div>
					<label for="edit-date">Date published: </label>
					<input type="date" name="edit-date" id="edit-date">
				</div>
				<div>
					<label for="edit-pages">Number of pages: </label>
					<input type="text" name="edit-pages" id="edit-pages">
				</div>
				<div>
					<label for="edit-notes">Notes:</label>
					<textarea id="edit-notes" name="edit-notes" rows="4" cols="50"></textarea> 
				</div>
				<input type="hidden" id="edit-book-id" name="edit-book-id" value="">
				<button type="sumbit" name="edit-book">Edit</button>
				<button type="button" onclick="hideAllContainers()">Cancel</button>
			</form>
		</div>
		
		<div id="delete-container" style="display: none">
			<h1>Book deletion</h1>
			<p>Are you sure you want to delete <span id="delete-name"></span>?</p>
			<form method="POST" name="delete-book" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
				<button type="sumbit" name="delete-book">Proceed</button>
				<input type="hidden" id="delete-book-id" name="delete-book-id" value="">
			</form>
			<button type="button" onclick="hideAllContainers()">Cancel</button>
		</div>

		<div id="add-container" style="display: none">
			<h1>Adding new book</h1>
			<form method="POST" name="add-book" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
				<div>
					<label for="add-name">Name*: </label>
					<input type="text" name="add-name" id="add-name" required>
				</div>
				<div>
					<label for="add-author">Author*: </label>
					<input type="text" name="add-author" id="add-author" required>
				</div>
				<div>
					<label for="add-genre">Genre*: </label>
					<input type="text" name="add-genre" id="add-genre" required>
				</div>
				<div>
					<label for="add-read">Read*: </label>
					<input type="checkbox" name="add-read" id="add-read">
				</div>
				<div>
					<label for="add-rating">Rating: </label>
					<select name="add-rating" id="add-rating">
						<option value="">No rating</option>
						<option value="0">0 ★</option>
						<option value="1">0.5 ★</option>
						<option value="2">1 ★</option>
						<option value="3">1.5 ★</option>
						<option value="4">2 ★</option>
						<option value="5">2.5 ★</option>
						<option value="6">3 ★</option>
						<option value="7">3.5 ★</option>
						<option value="8">4 ★</option>
						<option value="9">4.5 ★</option>
						<option value="10">5 ★</option>
					</select>
				</div>
				<div>
					<label for="add-isbn">ISBN (used for covers): </label>
					<input type="text" name="add-isbn" id="add-isbn">
				</div>
				<div>
					<label for="add-date">Date published: </label>
					<input type="date" name="add-date" id="add-date">
				</div>
				<div>
					<label for="add-pages">Number of pages: </label>
					<input type="text" name="add-pages" id="add-pages">
				</div>
				<div>
					<label for="add-notes">Notes:</label>
					<textarea id="add-notes" name="add-notes" rows="4" cols="50"></textarea> 
				</div>
				<button type="sumbit" name="add-book">Add</button>
				<button type="button" onclick="hideAllContainers()">Cancel</button>
			</form>
		</div>

		<script>
			let books = <?php echo json_encode(getArrayOfBooksForID($_SESSION['user']->getId()))?>;
			let phpSelf = '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>';
		</script>
		<script src="../javascript/booklist.js"></script>
	</body>
</html>