<?php
// This is the index view.
?>

<!DOCTYPE html>
<html lang="cs">
	<head>
		<meta charset="UTF-8">
    	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="icon" href="images/favicon.gif" type="image/x-icon">
		<link rel="stylesheet" href="css/global.css">
		<link rel="stylesheet" href="css/mainpage.css">
		<title>dobréčtení</title>
	</head>
	<body>
		<?php if (isset($_GET['deleted'])) { ?>
		<p>Account successfully deleted. Create a new one or log into another existing account.</p>
		<?php } ?>



		<main>
			<div class="logo"><h1>dobré<span>čtení</span></h1></div>
			<div id="container-login-register">
				<a href="views/login.php" id="login">Login</a>
				<a href="views/register.php">Register</a>
			</div>
		</main>
		
		

		<script>
			if (localStorage.getItem('theme') == null) {
    			localStorage.setItem('theme', 'light');
    			theme = localStorage.getItem('theme');
			}
		</script>
		<script src="javascript/theme.js"></script>
	</body>
</html>
