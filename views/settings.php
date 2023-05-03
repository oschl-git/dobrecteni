<?php
// This is the settings view.

require_once '../authentication/authentication_actions.php';
require_once '../data/database_access.php';

session_start();

?>

<!DOCTYPE html>
<html lang="cs">
	<head>
		<link rel="stylesheet" href="../css/global.css">
	</head>
	<body>
		<h1>Settings page</h1>
		<form method="POST" name="change-username" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			<h3>Change username</h3>	
			<div>
				<label for="new-username">New username: </label>
				<input type="text" name="new-username" id="new-username" required>
			</div>
			<button type="change-username" name="change-username">Change</button>
		</form>
		<form method="POST" name="change-password" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			<h3>Change password</h3>
			<div>
				<label for="old-password">Verify old password: </label>
				<input type="password" name="old-password" id="old-password" required>
			</div>
			<div>
				<label for="new-password">New password: </label>
				<input type="password" name="new-password" id="new-password" required>
			</div>
			<button type="change-password" name="change-password">Change</button>
		</form>
		<div>
			<h3>Dark theme</h3>
			<div>
				<label for="dark-theme">Use dark theme </label>
				<input type="checkbox" name="dark-theme" id="dark-themes" required>
			</div>
		</div>
	</body>
</html>