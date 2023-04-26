<?php
// This script contains functions for authentication actions.

require_once 'user.php';

// Checks user login, returns User object, throws exception if something goes wrong.
function login(string $username, string $password): ?User {
	global $pdo;
	
	$username = trim($username);
	$password = trim($password);
	
	$query = 'SELECT * FROM users WHERE (username = :username) AND (enabled = 1)';

	$values = array(':username' => $username);
	
	try {
		$res = $pdo->prepare($query);
		$res->execute($values);
	}
	catch (PDOException $e) {
		throw new Exception('Database query error.');
	}
	
	$row = $res->fetch(PDO::FETCH_ASSOC);

	if (!is_array($row)) throw new Exception('User doesn\'t exist.');
	
	if (password_verify($password, $row['password'])) {
		return new User(getIdFromUsername($username), $username);
	}
	else {
		throw new Exception('Invalid password.');
	}
}

// Logs out the current user.
function logout(): void {
	$_SESSION["user"] = null;
}

// Registers a user to the database.
function addUser(string $username, string $password): void {
	global $pdo;
	
	$username = trim($username);
	$password = trim($password);
	
	if (!isUsernameValid($username)) throw new Exception('Invalid username.');
	if (!isPasswordValid($password)) throw new Exception('Invalid password.');
	if (!is_null(getIdFromUsername($username))) { 
		throw new Exception('Username not available.');
	}
	
	$query = 'INSERT INTO users (username, password) VALUES (:username, :password)';
	$hash = password_hash($password, PASSWORD_DEFAULT);
	$values = array(':username' => $username, ':password' => $hash);
	
	try {
		$res = $pdo->prepare($query);
		$res->execute($values);
	}
	catch (PDOException $e) {
		throw new Exception('Database query error.');
	}
}

// Deletes a user from the database.
function deleteUser(int $id): void {
	global $pdo;
	
	$query = 'DELETE FROM users WHERE (id = :id)';
	
	$values = array(':id' => $id);
	
	try {
		$res = $pdo->prepare($query);
		$res->execute($values);
	}
	catch (PDOException $e) {
	   throw new Exception('Database query error.');
	}
}

// Returns id of the provided username, if it exists. If not, returns null.
function getIdFromUsername(string $username): ?int {
	global $pdo;
	
	if (!isUsernameValid($username)) throw new Exception('Invalid username.');
	
	$id = NULL;

	$query = 'SELECT id FROM users WHERE (username = :username)';
	$values = array(':username' => $username);
	
	try {
		$res = $pdo->prepare($query);
		$res->execute($values);
	}
	catch (PDOException $e) {
		throw new Exception('Database query error.');
	}
	
	$row = $res->fetch(PDO::FETCH_ASSOC);
	
	if (is_array($row)) {
		$id = intval($row['id'], 10);
	}
	
	return $id;
}

// Checks requirements for usernames.
function isUsernameValid(string $username): bool {
	$valid = TRUE;
	
	$length = mb_strlen($username);
	if (($length < 2) || ($length > 64))
	{
		$valid = FALSE;
	}

	return $valid;
}

// Checks requirements for passwords.
function isPasswordValid(string $password): bool {
	$valid = TRUE;
	
	$length = mb_strlen($password);
	if (($length < 8) || ($length > 16))
	{
		$valid = FALSE;
	}
	
	return $valid;
}

?>