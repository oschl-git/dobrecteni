<?php
// This is the register view.

require_once '../authentication/user.php';
require_once '../authentication/authentication_actions.php';
require_once '../data/database_access.php';

session_start();

// Backend feedback variables:
$error;

// Redirects to the booklist page if user already logged in.
if (isset($_SESSION['user'])) {
	header('Location: ./booklist.php');
	exit();
}

// Handles the registration POST request.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$data;

	$data = [
		"username" => validate_input($_POST["username"]),
		"password" => validate_input($_POST["password"]),
		"password-verification" => validate_input($_POST["password-verification"]),
	];

	try
	{
		if ($data['password'] != $data['password-verification']) {
			throw new Exception('Passwords do not match.');
		}

		addUser($data['username'], $data['password']);
		header('Location: ./login.php?registered');
		exit();
	}
	catch (Exception $e)
	{
		$error = $e->getMessage();
	}
}
?>

<!DOCTYPE html>
<html lang="cs">
	<head>
		<script src="../javascript/head.js"></script>
		<link rel="stylesheet" href="../css/login-register.css">
		<title>dobréčtení - register</title>
	</head>
	<body>
		<!-- Shows backend feedback: -->
		<?php if (isset($error)) { ?>
		<div class="message">
			<p style="color: red;"><?php echo $error ?></p>
		</div>
		<?php }?>

		<header>
			<a href="../index.php"><h1 class="logo">dobré<span class="logo">čtení</span></h1></a>
		</header>

		<main>
			<form method="POST" name="register-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
				<h2>Register</h2>
				<div>
					<input type="text" name="username" id="username" placeholder="username" required>
					<input type="password" name="password" id="password" placeholder="password" required>
					<input type="password" name="password-verification" id="password-verification" placeholder="password-verification" required>
					<button type="sumbit" name="submit">Register</button>
				</div>
				<a href="login.php">Already registered? Login here.</a>
				<div id="constraints">
					<p>username must be >= 8 characters & <= 64 characters</p>
					<p>password must be >= 2 characters & <= 64 characters</p>
				</div>
			</form>
		</main>
		
		<script src="../javascript/theme.js"></script>
	</body>
</html>