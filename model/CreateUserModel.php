<?php

namespace model;

class CreateUserModel {
	private static $username = 'user';
	private static $password = 'pw';
	
	public function __construct() {
	}
	
	public function saveUser($userData){
		$user = $userData[self::$username];
		if($this->saveNewUser($userData) === true){
			return $user;
		}
		return false;
	}
	
	public function ifExists($userData){
		if(@file('Users/' . $userData[self::$username] . '.txt')){
			return true;
		}
		return false;		
	}
	
	private function saveNewUser($userData){
		$file = 'Users/' . $userData[self::$username] . '.txt';
		$data = $userData[self::$password];
		if(file_put_contents($file, $data) != FALSE){
			return true;
		}
		return false;
	}
}