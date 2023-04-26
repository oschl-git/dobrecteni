<?php

class Account
{
	/* Class properties (variables) */
	
	/* The ID of the logged in account (or NULL if there is no logged in account) */
	private $id;
	
	/* The name of the logged in account (or NULL if there is no logged in account) */
	private $username;
	
	/* TRUE if the user is authenticated, FALSE otherwise */
	private $authenticated;
	
	
	/* Public class methods (functions) */
	
	/* Constructor */
	public function __construct()
	{
		/* Initialize the $id and $name variables to NULL */
		$this->id = NULL;
		$this->username = NULL;
		$this->authenticated = FALSE;
	}
	
	/* Destructor */
	public function __destruct()
	{
		
	}
	
	/* "Getter" function for the $id variable */
	public function getId(): ?int
	{
		return $this->id;
	}
	
	/* "Getter" function for the $name variable */
	public function getUsername(): ?string
	{
		return $this->username;
	}
	
	/* "Getter" function for the $authenticated variable */
	public function isAuthenticated(): bool
	{
		return $this->authenticated;
	}
	
	/* Add a new account to the system and return its ID (the account_id column of the accounts table) */
	public function addUser(string $username, string $password): void
	{
		/* Global $pdo object */
		global $pdo;
		
		/* Trim the strings to remove extra spaces */
		$username = trim($username);
		$password = trim($password);
		
		/* Check if the user name is valid. If not, throw an exception */
		if (!$this->isUsernameValid($username))
		{
			throw new Exception('Invalid username.');
		}
		
		/* Check if the password is valid. If not, throw an exception */
		if (!$this->isPasswordValid($password))
		{
			throw new Exception('Invalid password.');
		}
		
		/* Check if an account having the same name already exists. If it does, throw an exception */
		if (!is_null($this->getIdFromUsername($username)))
		{
			throw new Exception('Username not available.');
		}
		
		/* Finally, add the new account */
		
		/* Insert query template */
		$query = 'INSERT INTO users (username, password) VALUES (:username, :password)';
		
		/* Password hash */
		$hash = password_hash($password, PASSWORD_DEFAULT);
		
		/* Values array for PDO */
		$values = array(':username' => $username, ':password' => $hash);
		
		/* Execute the query */
		try
		{
			$res = $pdo->prepare($query);
			$res->execute($values);
		}
		catch (PDOException $e)
		{
		   /* If there is a PDO exception, throw a standard exception */
		   throw new Exception('Database query error.');
		}
	}
	
	/* Delete an account (selected by its ID) */
	public function deleteAccount(int $id)
	{
		/* Global $pdo object */
		global $pdo;
		
		/* Check if the ID is valid */
		if (!$this->isIdValid($id))
		{
			throw new Exception('Invalid account ID.');
		}
		
		/* Query template */
		$query = 'DELETE FROM users WHERE (id = :id)';
		
		/* Values array for PDO */
		$values = array(':id' => $id);
		
		/* Execute the query */
		try
		{
			$res = $pdo->prepare($query);
			$res->execute($values);
		}
		catch (PDOException $e)
		{
		   /* If there is a PDO exception, throw a standard exception */
		   throw new Exception('Database query error.');
		}
		
		/* Values array for PDO */
		$values = array(':id' => $id);
		
		/* Execute the query */
		try
		{
			$res = $pdo->prepare($query);
			$res->execute($values);
		}
		catch (PDOException $e)
		{
		   /* If there is a PDO exception, throw a standard exception */
		   throw new Exception('Database query error.');
		}
	}
	
	/* Login with username and password */
	public function login(string $username, string $password): bool
	{
		/* Global $pdo object */
		global $pdo;
		
		/* Trim the strings to remove extra spaces */
		$username = trim($username);
		$password = trim($password);
		
		/* Look for the account in the db. Note: the account must be enabled (account_enabled = 1) */
		$query = 'SELECT * FROM users WHERE (username = :username) AND (enabled = 1)';
		
		/* Values array for PDO */
		$values = array(':username' => $username);
		
		/* Execute the query */
		try
		{
			$res = $pdo->prepare($query);
			$res->execute($values);
		}
		catch (PDOException $e)
		{
		   /* If there is a PDO exception, throw a standard exception */
		   throw new Exception('Database query error.');
		}
		
		$row = $res->fetch(PDO::FETCH_ASSOC);
		
		/* If there is a result, we must check if the password matches using password_verify() */
		if (is_array($row))
		{
			if (password_verify($password, $row['password']))
			{
				/* Authentication succeeded. Set the class properties (id and name) */
				$this->id = intval($row['id'], 10);
				$this->username = $username;
				$this->authenticated = TRUE;
				
				/* Finally, Return TRUE */
				return TRUE;
			}
			else {
				throw new Exception('Invalid password.');
			}
		}
		else {
			throw new Exception('Username doesn\'t exist.');
		}
	}
	
	/* A sanitization check for the account username */
	public function isUsernameValid(string $username): bool
	{
		/* Initialize the return variable */
		$valid = TRUE;
		
		/* Example check: the length must be between 8 and 16 chars */
		$len = mb_strlen($username);
		
		if (($len < 2) || ($len > 64))
		{
			$valid = FALSE;
		}
		
		/* You can add more checks here */
		
		return $valid;
	}
	
	/* A sanitization check for the account password */
	public function isPasswordValid(string $password): bool
	{
		/* Initialize the return variable */
		$valid = TRUE;
		
		/* Example check: the length must be between 8 and 16 chars */
		$len = mb_strlen($password);
		
		if (($len < 8) || ($len > 16))
		{
			$valid = FALSE;
		}
		
		/* You can add more checks here */
		
		return $valid;
	}
	
	/* A sanitization check for the account ID */
	public function isIdValid(int $id): bool
	{
		/* Initialize the return variable */
		$valid = TRUE;
		
		/* Example check: the ID must be between 1 and 1000000 */
		
		if (($id < 1) || ($id > 1000000))
		{
			$valid = FALSE;
		}
		
		/* You can add more checks here */
		
		return $valid;
	}
	

	/* Logout the current user */
	public function logout()
	{
		/* Global $pdo object */
		global $pdo;
		
		/* If there is no logged in user, do nothing */
		if (is_null($this->id))
		{
			return;
		}
		
		/* Reset the account-related properties */
		$this->id = NULL;
		$this->username = NULL;
		$this->authenticated = FALSE;
	}

	
	/* Returns the account id having $name as name, or NULL if it's not found */
	public function getIdFromUsername(string $username): ?int
	{
		/* Global $pdo object */
		global $pdo;
		
		/* Since this method is public, we check $name again here */
		if (!$this->isUsernameValid($username))
		{
			throw new Exception('Invalid username.');
		}
		
		/* Initialize the return value. If no account is found, return NULL */
		$id = NULL;
		
		/* Search the ID on the database */
		$query = 'SELECT id FROM users WHERE (username = :username)';
		$values = array(':username' => $username);
		
		try
		{
			$res = $pdo->prepare($query);
			$res->execute($values);
		}
		catch (PDOException $e)
		{
		   /* If there is a PDO exception, throw a standard exception */
		   throw new Exception('Database query error.');
		}
		
		$row = $res->fetch(PDO::FETCH_ASSOC);
		
		/* There is a result: get it's ID */
		if (is_array($row))
		{
			$id = intval($row['id'], 10);
		}
		
		return $id;
	}
}
