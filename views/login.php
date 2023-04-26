<?php

session_start();

require '../account.php';
require '../database_access.php';


$account = new Account();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$data;
	$result;

	$data = [
		"username" => validate_input($_POST["username"]),
		"password" => validate_input($_POST["password"]),
	];

	try
	{
		$result = $account->login($data['username'], $data['password']);
	}
	catch (Exception $e)
	{
		echo $e->getMessage();
		die();
	}

	if ($result) {
		$_SESSION["user_id"] = $account->getIdFromUsername($data['username']);
		$_SESSION["username"] = $data['username'];

		header('Location: ./booklist.php');
		exit();
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
		<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
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