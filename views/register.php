<?php

require_once '../authentication/user.php';
require_once '../authentication/authentication_actions.php';
require_once '../data/database_access.php';

session_start();

$error;

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

	try
	{
		addUser($data['username'], $data['password']);
		header('Location: ./login.php?registered');
		exit();
	}
	catch (Exception $e)
	{
		$error = $e->getMessage();
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
	<head>
		<link rel="stylesheet" href="../css/global.css">
	</head>
	<body>
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
			<button type="sumbit" name="submit">Register</button>
		</form>
	</body>
</html>