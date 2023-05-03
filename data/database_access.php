<?php
// This script handles accessing the MariaDB database.

$config = include '../config.php';

$host = $config['host'];
$username = $config['username'];
$password = $config['password'];
$schema = $config['schema'];

$pdo = NULL;

$dsn = 'mysql:host=' . $host . ';dbname=' . $schema;

try {  
   $pdo = new PDO($dsn, $username,  $password);
   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
   print_r($e);
   die();
}

// Makes sure the provided input doesn't contain anything sus.
function validate_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

?>