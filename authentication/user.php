<?php
// This class is used for storing data of the currently logged user.

class User {
	private int $id;
	private string $username;


	public function __construct(int $id, string $username) {
		$this->id = $id;
		$this->username = $username;
	}

	public function getId(): int {
		return $this->id;
	}

	public function getUsername(): string {
		return $this->username;
	}
}

?>