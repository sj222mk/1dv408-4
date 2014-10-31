<?php

namespace model;

class LoginModel {
	private static $agent = 'agent';
	private static $addr = 'addr';
	private static $username = 'user';
	private static $password = 'pw';
	private static $sessionTimeStamp = 'time';
	
	public function __construct() {
	}

	public function checkUser($userData){
		if($this->ifExists($userData)){
			if($this->varifyUser($userData)){
				$_SESSION[self::$username] = $userData[self::$username];
				return true;
			}
		}
		return false;
	}
	
	private function ifExists($userData){
		if(@file('Users/' . $userData[self::$username] . '.txt')){
			return true;
		}
		return false;		
	}
	
	private function varifyUser($userData){
		$userData = $this->trimArray($userData);
		$user = @file('Users/' . $userData[self::$username] . '.txt');
		if($user[0] === $userData['pw']){
			return true;
		}	
		return false;
	}
	
	private function trimArray($array){
		foreach ($array as $line) {
			$line = trim($line);
			}
		return $array;
	}
	
	public function doesClientExist($clientID){
		$savedUser = $this->getUserSession([self::$username]);
		if($clientID === $savedUser){
			if($savedUser === $this->doesSessionExist()){
				if($this->isSessionNew() === true){
					return array(self::$username => $savedUser, self::$sessionTimeStamp => true); 
				}
				else{
					return array(self::$username => $savedUser, self::$sessionTimeStamp => false); 
				}
			}
		}
		return false;
	}
	
	private function isSessionNew(){
		if(isset($_SESSION[self::$sessionTimeStamp])){
			if($_SERVER['REQUEST_TIME'] === $_SESSION[self::$sessionTimeStamp]){
				return true;
			}
		}
		return false;		
	}
	
	public function doesSessionExist(){
		$savedSession = $this->getSavedSession();
		if($savedSession != ""){
			if($_SERVER['HTTP_USER_AGENT'] === $savedSession[self::$agent] && $_SERVER['REMOTE_ADDR'] === $savedSession[self::$addr]){
				return $savedSession[self::$username];
			}
		}
		return false;
	}
	
	private function getUserSession(){
		$data = "";
		
		if(isset($_SESSION[self::$username]) && isset($_SESSION[self::$password])){
			$data = array("user" => $_SESSION[self::$username], "pw" => $_SESSION[self::$password]);
		}	
		return $data;
	}
	
	private function getSavedSession(){
		$data = "";
		
		if(isset($_SESSION[self::$agent]) && isset($_SESSION[self::$addr]) && isset($_SESSION[self::$username])){
			$data = array(self::$agent => $_SESSION[self::$agent], self::$addr => $_SESSION[self::$addr], self::$username => $_SESSION[self::$username]);
		}
		return $data;
	}
	
	public function saveUserSession($userData){
		try{
			$_SESSION[self::$sessionTimeStamp] = $_SERVER['REQUEST_TIME'];
			$_SESSION[self::$password] = $userData['pw'];
			$this->saveSession($userData[self::$username]);
			return true;
		}
		catch(Exception $e){
			return false;
		}
	}
	
	public function saveSession($userName){
		try{
			$_SESSION[self::$agent] = $_SERVER['HTTP_USER_AGENT'];
			$_SESSION[self::$addr] = $_SERVER['REMOTE_ADDR'];
			$_SESSION[self::$username] = $userName;
			return true;
		}
		catch(Exception $e){
			return false;
		}
	}
	
	public function unsetSession(){
			try{
				if(isset($_SESSION[self::$username])){
					session_unset(self::$username); 	
				}
				if(isset($_SESSION[self::$password])){
					session_unset(self::$password);
				}
				if(isset($_SESSION[self::$agent])){
					session_unset(self::$agent);
				}
				if(isset($_SESSION[self::$addr])){
					session_unset(self::$addr);
				}
				return true;
			}
			catch(Exception $e){
				return false;
			}
		}
}