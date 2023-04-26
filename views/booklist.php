<?php
	session_start();
?>

<!DOCTYPE html>
<html lang="cs">
	<body>
		<h1>Book stránka uživatele</h1>
		<p>Id: <?php echo htmlspecialchars($_SESSION["user_id"]); ?></p>
		<p>Username: <?php echo htmlspecialchars($_SESSION["username"]); ?></p>
	</body>
</html>