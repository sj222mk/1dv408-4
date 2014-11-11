<?php

namespace view;

class CookieStorage {
	private static $userCookie = 'Username';
	private static $passwordCookie = 'Password';
	private static $user = 'user';
	private static $password = 'password';
	
	public function getSessid(){
		$cookie = session_id();
		return $cookie;
	}
	
	public function save($name, $string) {
		$cookieTime = 3600;	//Cookiers giltighetstid = 1h
		
		if(setcookie($name, $string, time() + $cookieTime)){
			return true;
		}
		return false;
	}
	
	public function saveUsercookies($userData){
		if (($this->save('Username', $userData[self::$user])) 
			&& ($this->save('Password', password_hash($userData[self::$password], PASSWORD_BCRYPT)))
		){								
			return true;
		}
		return false;
	}
	
	public function checkUserCookies(){
		if ((isset($_COOKIE[self::$userCookie])) && ($_COOKIE[self::$userCookie] != "") &&
			(isset($_COOKIE[self::$passwordCookie])) && ($_COOKIE[self::$passwordCookie] != "")){
			return true;
		}
		return false;
	}
	
	public function loadUserFromCookie(){
		$data = "";
		
		$user = $this->loadCookie(self::$userCookie);
		$password = $this->loadCookie(self::$passwordCookie);
		
		if (($user != "") && ($password != "")){
			$data = array('user' => $user, 'password' => $password);
			}
		else{
			$data = false;
		}
		return $data;
	}
	
	public function removeUserCookies(){
		try{
			$this->remove(self::$userCookie);
			$this->remove(self::$passwordCookie);
			return true;
		}
		catch(exception $e){		
			return false;
		}
	}
	
	public function loadCookie($name) {
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
	
	
