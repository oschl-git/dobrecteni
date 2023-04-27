<?php
	require_once '../authentication/user.php';
	require_once '../data/database_access.php';
	require_once '../data/book.php';

	session_start();

	if (!isset($_SESSION['user'])) {
		header('Location: ./login.php');
		exit();
	}

	if (isset($_POST['logout'])) {
		$_SESSION['user'] = null;
		header('Location: ./login.php');
		exit();
	}
?>

<!DOCTYPE html>
<html lang="cs">
	<body>
		<h1>Book stránka uživatele</h1>
		<p>Id: <?php echo htmlspecialchars($_SESSION['user']->getId()); ?></p>
		<p>Username: <?php echo htmlspecialchars($_SESSION['user']->getUsername()); ?></p>
		<p><?php echo json_encode(getArrayOfBooksForID($_SESSION['user']->getId()))?></p>

		<form method="POST" name="logout" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			<button type="sumbit" name="logout">Logout</button>
		</form>
	</body>
</html>