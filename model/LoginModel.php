<?php

namespace model;

class LoginModel {
	private static $expireTime = 3600; //Satta kakors giltighetstid: 60*60 = 3600 = 1h
	
	public function __construct() {
	}

	//Kontrollerar om en användare är sparad i sessionen
	public function checkUser($userData){
		if($this->checkIfMemberExists($userData['user'])){
			if($this->verifyMember($userData)){
				$_SESSION['username'] = $userData['user'];
				return true;
			}
		}
		return false;
	}
	
	//Kontroll om användaren är medlem
	private function checkIfMemberExists($user){
		if(@file('Members/' . $user . '.txt')){
			return true;
		}
		return false;		
	}
	
	//Verifierar användarens inloggningsuppgifter
	private function verifyMember($userData){
		$userData = $this->trimArray($userData);
		$user = $userData['user'];
		$userRecord = $this->getMemberData($user);
		if($userRecord != false){
			if($userRecord[0] === $userData['password']){
			return true;
			}	
		}
		return false;
	}
	
	//Hämtar sparad medlemsdata
	private function getMemberData($user){
		$userRecords = @file('Members/' . $user . '.txt');
		if($userRecords != ""){
			return $userRecords;
		}	
		return false;
	}
	
	private function trimArray($array){
		foreach ($array as $line) {
			$line = trim($line);
			}
		return $array;
	}
	
	//Kollar om användaren finns sparad som ihågkommen
	private function ifClientsessionIsRemembered($user){
	 	if(@file('Sessions/' . $user . 'session.txt')){
			return true;
		}
		return false;		
	}
	
	//Hämtar användaruppgifter från textfil för ihågkommen användare
	private function getRememberedClientSettings($user){
		$user = @file('Sessions/' . $user . 'session.txt');
		return $user;
	}
	
	//Kollar om användaren tidigare bett om att hållas inloggad
	public function verifyRememberedClient($clientArray, $clientSession){
		$rememberedUserArray;
		$currentClientString;
		$memberData;
		$user = $clientArray['user'];
		
		if($this->ifClientsessionIsRemembered($user) && $this->checkIfMemberExists($user)){
			$memberData = $this->getMemberData($user);
			if($memberData != "" && password_verify($memberData[0], $clientArray['password'])){
				$rememberedUserArray = $this->getRememberedClientSettings($user);
				if($rememberedUserArray != false && $rememberedUserArray != ""){
					$currentClientString = $clientSession['address'] . $clientSession['agent'] . "\n";
					if($rememberedUserArray[0] === $currentClientString && $rememberedUserArray[1] > time()){
						return $user;	
					}
				}
			}
			else{//Tar bort sparad användare då uppgifterna inte är giltiga längre
				$this->removeRememberedUserSession($user); 
			}
			
		}
		return false;
	}
	
	//Sparar användare som vill bli ihågkommen
	public function saveRememberedUserSession($userData, $serverSession){
		$file = 'Sessions/' . $userData['user'] . 'session.txt';
		$data = $serverSession['address'] . $serverSession['agent'] . "\n" . time() + self::$expireTime;
		if(file_put_contents($file, $data) != FALSE){
			return true;
		}
		return false;
	}	 
	
	//Kontrollerar användare mot sparad sessionsdata
	public function doesSessionExist($clientSession){
		$savedSession = $this->getSavedSession();
		if($savedSession != "" && $clientSession != ""){
			if($clientSession['agent'] === $savedSession['agent'] && $clientSession['address'] === $savedSession['address']){
				return $savedSession['user'];
			}
		}
		return false;
	}
	
	//Tar bort textfil med sparad sessionsdata
	public function removeRememberedUserSession($user){
		if($this->ifClientsessionIsRemembered($user)){
			unlink('Sessions/' . $user . 'session.txt');
			if($this->ifClientsessionIsRemembered($user) === FALSE){ //Kontrollerar att filen tagits bort
				return true;
			}
			return false;
		}
	return true;
	}
	
	//Hämtar användardata sparat i sessionen
	private function getSavedSession(){
		$data = "";
		
		if(isset($_SESSION['agent']) && isset($_SESSION['address']) && isset($_SESSION['username'])){
			$data = array('agent' => $_SESSION['agent'], 'address' => $_SESSION['address'], 'user' => $_SESSION['username']);
		}
		return $data;
	}
	
	//Sparar användardata i sessionen för användare som INTE vill kommas ihåg
	public function saveUserSession($userData, $serverData){
		try{
			$_SESSION['user'] = $userData['user'];
			$_SESSION['password'] = $userData['password'];
			$_SESSION['address'] = $serverData['address'];
			$_SESSION['agent'] = $serverData['agent'];
			return true;
		}
		catch(Exception $e){
			return false;
		}
	}
	
	//Tar bort all sparad sessionsdata
	public function unsetSession(){
		try{
			if(isset($_SESSION['user'])){
				session_unset('user'); 	
			}
			if(isset($_SESSION['password'])){
				session_unset('password');
			}
			if(isset($_SESSION['address'])){
				session_unset('address');
			}
			if(isset($_SESSION['agent'])){
				session_unset('agent');
			}
			return true;
			//$_SESSION = array(); //Nollställer värden
			//session_destroy(); //Förstör sparad session
			//return true;
		}
		catch(Exception $e){
			return false;
		}
	}
}