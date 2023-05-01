<?php
// This script handles accessing the MariaDB database.

$host = 'localhost';
$user = 'root';
$passwd = '';
$schema = 'dobrecteni';

$pdo = NULL;

$dsn = 'mysql:host=' . $host . ';dbname=' . $schema;

try {  
   $pdo = new PDO($dsn, $user,  $passwd);
   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
   print_r($e);
   die();
}
?>