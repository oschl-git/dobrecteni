<?php
// This is the login view.

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

// Handles logging in.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$data;

	$data = [
		"username" => validate_input($_POST["username"]),
		"password" => validate_input($_POST["password"]),
	];

	try {
		$_SESSION["user"] = login($data['username'], $data['password']);
		header('Location: ./booklist.php');
		exit();
	}
	catch (Exception $e) {
		$error = $e->getMessage();
	}
}
?>

<!DOCTYPE html>
<html lang="cs">
	<head>
		<link rel="stylesheet" href="../css/global.css">
		<link rel="stylesheet" href="../css/header.css">
		<link rel="stylesheet" href="../css/login.css">
	</head>
	<body>
		<!-- Shows backend feedback: -->
		<?php if (isset($error)) { ?>
		<p><?php echo $error ?></p>
		<?php } ?>
		<?php if (isset($_GET['registered'])) { ?>
		<p>Successfully registered. Please, log in.</p>
		<?php } ?>

		<header>
			<a href="../index.php"><h1 class="logo">dobré<span class="logo">čtení</span></h1></a>
		</header>

		<div class="container">
			<form method="POST" name="login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
				<h2>Login page</h2>
				<div>
					<input type="text" name="username" id="username" placeholder="username" required>
				</div>
				<div>
					<input type="password" name="password" id="password" placeholder="password" required>
				</div>
				<button type="sumbit" name="submit">Login</button>
				<a href="register.php">Register here.</a>
			</form>
		</div>
		
		
		<script src="../javascript/theme.js"></script>
	</body>
</html>