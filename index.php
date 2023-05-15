<?php
// This is the index view.
?>

<!DOCTYPE html>
<html lang="cs">
	<head>
		<link rel="stylesheet" href="css/global.css">
		<link rel="stylesheet" href="css/mainpage.css">
	</head>
	<body>
		<?php if (isset($_GET['deleted'])) { ?>
		<p>Account successfully deleted. Create a new one or log into another existing account.</p>
		<?php } ?>


		
		<h1>dobré<span>čtení</span></h1>
		<div class="container">
			<a href="views/login.php">Login</a>
			<a href="views/register.php">Register</a>
		</div>
		


		<script>
			if (localStorage.getItem('theme') == null) {
    		localStorage.setItem('theme', 'light');
    		theme = localStorage.getItem('theme');
			}
		</script>
		<script src="javascript/theme.js"></script>
		<script src="javascript/logo.js"></script>
	</body>
</html>