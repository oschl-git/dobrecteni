<?php
// This is the booklist view.

require_once '../authentication/user.php';
require_once '../data/database_access.php';
require_once '../data/book.php';

session_start();

// Backend feedback variables:
$error;
$success;

// Redirects to the login page if no user is logged in.
if (!isset($_SESSION['user'])) {
	header('Location: ./login.php');
	exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
	// Handles the add-book POST request.
	if (isset($_POST['add-book'])) {
		try {
			addBookToDatabase(
				validate_input($_SESSION['user']->getId()),
				validate_input($_POST['add-name']),
				validate_input($_POST['add-author']),
				validate_input($_POST['add-genre']),
				isset($_POST['add-read']) ? true : false,
				$_POST['add-rating'] != '' ? validate_input((int)$_POST['add-rating']) : null,
				$_POST['add-isbn'] != '' ? validate_input($_POST['add-isbn']) : null,
				$_POST['add-date'] != '' ? validate_input($_POST['add-date']) : null,
				$_POST['add-pages'] != '' ? validate_input((int)$_POST['add-pages']) : null,
				$_POST['add-notes'] != '' ? validate_input($_POST['add-notes']) : null,
			);
			$success = 'Book ' . validate_input($_POST['add-name']) . ' successfully added.';
		}
		catch (Exception $e) {
			$error = $e->getMessage();
		}
	}

	// Handles the edit-book POST request.
	else if (isset($_POST['edit-book'])) {
		try {
			editBookInDatabase(
				validate_input($_POST['edit-book-id']),
				validate_input($_SESSION['user']->getId()),
				validate_input($_POST['edit-name']),
				validate_input($_POST['edit-author']),
				validate_input($_POST['edit-genre']),
				isset($_POST['edit-read']) ? true : false,
				isset($_POST['edit-rating']) ? validate_input((int)$_POST['edit-rating']) : null,
				$_POST['edit-isbn'] != '' ? validate_input($_POST['edit-isbn']) : null,
				$_POST['edit-date'] != '' ? validate_input($_POST['edit-date']) : null,
				$_POST['edit-pages'] != '' ? validate_input((int)$_POST['edit-pages']) : null,
				$_POST['edit-notes'] != '' ? validate_input($_POST['edit-notes']) : null,
			);
			$success = 'Book ' . validate_input($_POST['edit-name']) . ' successfully modified.';
		}
		catch (Exception $e) {
			$error = $e->getMessage();
		}
	}

	// Handles the delete-book POST request.
	else if (isset($_POST['delete-book'])) {
		try {
			deleteBookFromDatabase($_POST['delete-book-id'], $_SESSION['user']->getId());
			$success = 'Book successfully deleted.';
		}
		catch (Exception $e) {
			$error = $e->getMessage();
		}
	}

	// Handles the log-out POST request.
	else if (isset($_POST['logout'])) {
		$_SESSION['user'] = null;
		header('Location: ../index.php');
		exit();
	}
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

		<!-- Shows backend feedback: -->
		<?php if (isset($error)) { ?>
		<p id="error-message" style="color: red"><?php echo $error ?></p>
		<?php } ?>
		<?php if (isset($success)) { ?>
		<p id="success-message" style="color: green"><?php echo $success ?></p>
		<?php } ?>

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


		<!-- Container for showing book details. -->
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
			<button type="button" onclick="editBook()">Edit</button>
			<button type="button" onclick="hideAllContainers()">Close</button>
		</div>


		<!-- Container for editing books. -->
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
		

		<!-- Container for deleting books. -->
		<div id="delete-container" style="display: none">
			<h1>Book deletion</h1>
			<p>Are you sure you want to delete <span id="delete-name"></span>?</p>
			<form method="POST" name="delete-book" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
				<button type="sumbit" name="delete-book">Proceed</button>
				<input type="hidden" id="delete-book-id" name="delete-book-id" value="">
			</form>
			<button type="button" onclick="hideAllContainers()">Cancel</button>
		</div>


		<!-- Container for adding new books. -->
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
			// This script is used to initialise variables with data received from PHP. It must be
			// above all external scripts that utilise them.

			let books = <?php echo json_encode(getArrayOfBooksForID($_SESSION['user']->getId()))?>;
			let phpSelf = '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>';
		</script>
		<script src="../javascript/booklist.js"></script>
	</body>
</html>