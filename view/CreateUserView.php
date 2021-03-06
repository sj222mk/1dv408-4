<?php

namespace view;

require_once("./view/CookieStorage.php");

class CreateUserView {
	private $cookies;
	private $userMessage = "";
	private static $messageCookie = "Message";
	private static $outLoggedMessage = "Du har nu loggat ut";
	private static $userID = 'userID';
	private static $password1 = "PasswordID1"; 
	private static $password2 = "PasswordID2"; 
	private $username = "";
	private static $register = "register";
	
	private static $noUserNameMessage = "Användarnamn saknas";
	private static $noPasswordMessage = "Lösenord saknas";
	private static $noMatchMessage = "Lösenorden matchar inte";
	private static $userToFewMessage = "Användarnamnet har för få tecken. Minst 3 tecken";
	private static $userToManyMessage = "Användarnamnet har för många tecken. Max 20 tecken";
	private static $passwordToFewMessage = "Lösenorden har för få tecken. Minst 6 tecken";
	private static $passwordToManyMessage = "Lösenorden har för många tecken. Max 20 tecken";
	private static $userHasInvalidCharacters = "Användarnamnet innehåller ogiltiga tecken";
	
		
	public function __construct() {
		$this->cookies = new \view\CookieStorage();
	}
	
	public function setUserMessage($message){
		$this->userMessage = $message;
	}
	
	public function setUsername($name){
		$this->username = $name;
	}
	
	public function didUserPressRegister(){
		$strippedname;
		if (isset($_POST["Register"])){
			if($_POST[self::$userID] != ""){
				$this->username = $_POST[self::$userID];
				$strippedName = strip_tags($this->username);
				if($strippedName != $this->username){
					$this->userMessage = self::$userHasInvalidCharacters;
					$this->username = $strippedName;
					$this->username = trim($this->username);
				}
				else{
				$this->userMessage = "";
				}
			}
			else{
				$this->userMessage = self::$noUserNameMessage;
			}
			
			if($_POST[self::$password1] === "" || $_POST[self::$password2] === ""){
				if($this->userMessage === ""){	
					$this->userMessage = self::$noPasswordMessage;
				}
				else{
					$this->userMessage .= "<br>" . self::$noPasswordMessage;
				}
			$this->cookies->save(self::$messageCookie, $this->userMessage);
			return false;
			}
		if($_POST[self::$userID] != "" && $_POST[self::$password1] != "" && $_POST[self::$password2] != ""){
				return true;
			}
		}
	}
	
	public function didUserPressGoBack(){
		if(key($_GET) === '?'){
			return true;
		}
		return false;
	}
	
	public function didUserFillFormCorrectly(){
		$this->userMessage = "";
		if($this->isUserCorrect() && $this->isPasswordCorrect()){
			return $this->getUserData();
			}
		return false;
	}
	
	private function isUserCorrect(){
		$user = $_POST[self::$userID];
		if(strlen($user) < 3){
			$this->userMessage = self::$userToFewMessage;
		}
		elseif(strlen($_POST[self::$userID]) > 20){
			$this->userMessage = self::$userToFewMessage;
		}
		else{
			return true;
		}
		$this->cookies->save(self::$messageCookie, $this->userMessage);
		return false;
	}
	
	private function isPasswordCorrect(){
		$password1 = $_POST[self::$password1];
		$password2= $_POST[self::$password2];
		
		if(strlen($password1) < 6){
			$passwordMessage = self::$passwordToFewMessage;
		}
		elseif(strlen($password1) > 20){
			$passwordMessage = self::$passwordToManyMessage;
		}
		elseif($password1 != $password2){
			$passwordMessage = self::$noMatchMessage;
		}
		else{
			return true;
		}
		if($this->userMessage === ""){
			$this->userMessage = $passwordMessage;
		}
		else{
			$this->userMessage .= "<br>" .  $passwordMessage; 
		}
		$this->cookies->save(self::$messageCookie, $this->userMessage);
		return false;
	}
	
	private function getUserData(){
		$pw = $_POST[self::$password1];
		$pw = trim($pw);
		
		$data = array("user" => $this->username, "pw" => $pw);
		if ($data != ""){
			return $data;
		}	
		return false;
	}
	
	private function setNewestUserMessage(){
		if($this->userMessage === ""){
			$this->userMessage = $this->cookies->loadCookie(self::$messageCookie); 
		}
		
		return $this->userMessage;
	}
	
	public function showCreateUser() {
		$userMessage = $this->setNewestUserMessage();
		
		$user = "''";
		if($this->username != ""){
			$user = $this->username;
		}
		
		$ret = "<header>
					<h2>Ej inloggad</h2> 
				</header>
				<article>
					<form method='post'>
						<p><a href='?'>Tillbaka</a></p>
					</form>
				</article>
				<article>
					<fieldset>
						<legend>Registrera ny användare - Skriv in användarnamn och lösenord</legend>
						<form method='post'>
						<p>$userMessage</p> 
						<p><label for='UserID'>Namn : </label>
						<input id='UserID' name='userID' type='text' value=$user></p>
						<p><label for='PasswordID1'>Lösenord : </label>
						<input id='PasswordID1' name='PasswordID1' type='password' value=''></p>
						<p><label for='PasswordID2'>Repetera lösenord : </label>
						<input id='PasswordID2' name='PasswordID2' type='password' value=''></p>
						<p>Skicka :  
						<button type='submit' name='Register'>Registrera</button></p>
						
						</form>
					</fieldset>
				</article>";
		
		return $ret;
	}
}

