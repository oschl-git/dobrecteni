<?php
// This is the settings view.

require_once '../authentication/authentication_actions.php';
require_once '../data/database_access.php';

session_start();

$success;
$errror;

// Redirects to the login page if no user is logged in.
if (!isset($_SESSION['user'])) {
	header('Location: ./login.php');
	exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
	// Handles the change-username POST request.
	if (isset($_POST['change-username'])) {
		try {
			$new_username = validate_input($_POST['new-username']);

			changeUsername($_SESSION['user']->getId(), $new_username);
			$_SESSION['user']->setUsername($new_username);
			$success = 'Username successfully changed.';
		}
		catch (Exception $e) {
			$error = $e->getMessage();
		}
	}
		
	// Handles the change-password POST request.
	else if (isset($_POST['change-password'])) {
		try {
			$old_password = validate_input($_POST['old-password']);
			$new_password = validate_input($_POST['new-password']);
			$new_password_veficiation = validate_input($_POST['new-password-verification']);

			if ($new_password != $new_password_veficiation) {
				throw new Exception('Passwords do not match.');
			}

			changePassword($_SESSION['user']->getId(), $old_password, $new_password);
			$success = 'Password successfully changed.';
		}
		catch (Exception $e) {
			$error = $e->getMessage();
		}
	}

	// Handles the delete-account POST request.
	else if (isset($_POST['delete-account'])) {
		try {
			$password = validate_input($_POST['delete-password']);
			deleteUser($_SESSION['user']->getId(), $password);
			logout();
			header('Location: ../index.php?deleted');
			exit();
		}
		catch (Exception $e) {
			$error = $e->getMessage();
		}
	}
}
?>

<!DOCTYPE html>
<html lang="cs">
	<head>
		<script src="../javascript/head.js"></script>
		<link rel="stylesheet" href="../css/settings.css">
		<title>dobréčtení - settings</title>
	</head>
	<body>
		<!-- Shows backend feedback: -->
		<?php if (isset($error)) { ?>
		<div class="message">
			<p id="error-message" style="color: red"><?php echo $error ?></p>
		</div>
		<?php } ?>
		<?php if (isset($success)) { ?>
		<div class="message">
			<p id="success-message" style="color: green"><?php echo $success ?></p>
		</div>
		<?php } ?>

		

		<header>
			<a href="../index.php"><h1 class="logo">dobré<span class="logo">čtení</span></h1></a>
		</header>

		<main>
			<div id="top">
				<div id="undertop">
					<h2>Settings</h2>
					<a href="booklist.php" id="return">Return</a>
				</div>
			</div>
			
			<form method="POST" name="change-username" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
				<h3>Change username</h3>	
				<div>
					<input type="text" name="new-username" id="new-username" placeholder="new username" required>
					<button type="submit" name="change-username">Change</button>
				</div>
			</form>
			<form method="POST" name="change-password" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
				<h3>Change password</h3>
				<div>
					<input type="password" name="old-password" id="old-password" placeholder="verify old password" required>
					<input type="password" name="new-password" id="new-password" placeholder="new password" required>
					<input type="password" name="new-password-verification" id="new-password-verification" placeholder="new password again" required>
					<button type="submit" name="change-password">Change</button>
				</div>
			</form>
			<form action="">
				<h3>Dark theme</h3>	
				<div id="theme">
					<label for="dark-theme">Use dark theme </label>
					<input type="checkbox" name="dark-theme" id="dark-themes" onclick="changeTheme(this)" required>
				</div>
			</form>
			<form method="POST" name="delete-account" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
				<h3>Delete account</h3>
				<h5>Warning: This action cannot be undone and will result in loss of all data connected to this account.</h5>
				<div>
					<input type="password" name="delete-password" id="delete-password" placeholder="verify password" required>
					<button type="submit" name="delete-account">Permanently delete account</button>
				</div>
			</form>
		</main>
	
		<script>
			if (localStorage.getItem('theme') == 'dark') document.getElementById('dark-themes').checked = true;
		</script>
		<script src="../javascript/theme.js"></script>
	</body>
</html>