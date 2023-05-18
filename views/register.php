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
		<link rel="stylesheet" href="../css/global.css">
	</head>
	<body>
		<!-- Shows backend feedback: -->
		<?php if (isset($error)) { ?>
		<p><?php echo $error ?></p>
		<?php } ?>

		<h1>Register page</h1>
		<form method="POST" name="register-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			<div>
				<label for="username">Username: </label>
				<input type="text" name="username" id="username" required>
			</div>
			<div>
				<label for="password">Password: </label>
				<input type="password" name="password" id="password" required>
			</div>
			<div>
				<label for="password-verification">Password again: </label>
				<input type="password" name="password-verification" id="password-verification" required>
			</div>
			<button type="sumbit" name="submit">Register</button>
		</form>
		<a href="login.php">Already registered? Login here.</a>
	</body>
</html>