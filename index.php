<?php
	session_start();

	if (isset($_SESSION['user'])) {
		header('Location: ./views/booklist.php');
		exit();
	}
?>

<!DOCTYPE html>
<html lang="cs">
	<body>
		<h1>Dobréčtení</h1>
		<a href="views/login.php">Login</a>
		<a href="views/register.php">Register</a>
	</body>
</html>