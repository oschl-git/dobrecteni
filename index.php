<?php
// This is the index view.
?>

<!DOCTYPE html>
<html lang="cs">
	<head>
		<link rel="stylesheet" href="css/global.css">
	</head>
	<body>
		<?php if (isset($_GET['deleted'])) { ?>
		<p>Account successfully deleted. Create a new one or log into another existing account.</p>
		<?php } ?>

		<h1>Dobréčtení</h1>
		<a href="views/login.php">Login</a>
		<a href="views/register.php">Register</a>
	</body>
</html>