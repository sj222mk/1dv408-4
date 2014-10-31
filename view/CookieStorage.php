<?php

namespace view;

class CookieStorage {
	private static $userCookie = 'UserName';
	private static $passWordCookie = 'PassWord';
	private static $user = 'user';
	private static $password = 'pw';
	
	public function save($name, $string) {
		if(setcookie($name, $string, -1)){
			return true;
		}
		return false;
	}
	
	public function saveUser($userData){
		if (($this->save(self::$userCookie, $userData[self::$user])) 
			&& ($this->save(self::$passWordCookie, md5($userData[self::$password])))
		){
			return true;
		}
		return false;
	}
	
	public function checkUserCookie(){
		if ((isset($_COOKIE[self::$userCookie])) && (isset($_COOKIE[self::$passWordCookie]))){
			return true;
		}
		return false;
	}
	
	public function loadUserFromCookie(){
		$data = "";	
		
		if (($_COOKIE[self::$userCookie] != "") && ($_COOKIE[self::$passWordCookie] != "")){
			$data = $_COOKIE[self::$userCookie];
			}
		else{
			$data = false;
		}
		return $data;
	}
	
	public function removeUser(){
		if(isset($_COOKIE[self::$userCookie]) && $_COOKIE[self::$userCookie] != ""){
			setcookie(self::$userCookie, "", time() -10);
			if(isset($_COOKIE[self::$passWordCookie]) && $_COOKIE[self::$passWordCookie] != ""){
				setcookie(self::$passWordCookie, "", time() -10);			
				return true;
			}
			return false;
		}
		return false;
	}
	
	public function loadMessage($name) {
		if ((isset($_COOKIE[$name])) && ($_COOKIE[$name] != "")){
			$ret = $_COOKIE[$name];
		}
		else{
			$ret = "";
		}
		return $ret;
	}
	
	public function remove($name) {
		if (isset($_COOKIE[$name]) && $_COOKIE[$name] != ""){
			setcookie($name, "", time() -10);
			return true;
		}
		return false;
	}
		
}	
	
	
