<?php

require_once '../authentication/user.php';
require_once '../authentication/authentication_actions.php';
require_once '../data/database_access.php';

session_start();

if (isset($_SESSION['user'])) {
	header('Location: ./booklist.php');
	exit();
}

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
		echo $e->getMessage();
		die();
	}
}
 
function validate_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
?>

<!DOCTYPE html>
<html lang="cs">
	<body>
		<h1>Login page</h1>
		<form method="POST" name="login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			<div>
				<label for="username">Username: </label>
				<input type="text" name="username" id="username" required>
			</div>
			<div>
				<label for="password">Password: </label>
				<input type="password" name="password" id="password" required>
			</div>
			<button type="sumbit" name="submit">Login</button>
		</form>
	</body>
</html>