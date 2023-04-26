<?php
	require_once '../authentication/user.php';

	session_start();

	if (!isset($_SESSION['user'])) {
		header('Location: ./login.php');
		exit();
	}

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

		<form method="POST" name="logout-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			<button type="sumbit" name="submit">Logout</button>
		</form>
	</body>
</html>