<?php

namespace view;

require_once("./view/CookieStorage.php");

class CreateUserView {
	private $cookies;
	private $errorMessage = "";
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
	
		
	public function __construct() {
		$this->cookies = new \view\CookieStorage();
	}
	
	public function didUserPressRegister(){
		if (isset($_POST["Register"])){
			if($_POST[self::$userID] != ""){
				$this->username = $_POST[self::$userID];
				$this->errorMessage = "";
			}
			else{
				$this->errorMessage = self::$noUserNameMessage;
			}
			
			if($_POST[self::$password1] === "" || $_POST[self::$password2] === ""){
				if($this->errorMessage === ""){	
					$this->errorMessage = self::$noPasswordMessage;
				}
				else{
					$this->errorMessage .= "<br>" . self::$noPasswordMessage;
				}
			$this->cookies->save(self::$messageCookie, $this->errorMessage);
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
	/*private function resetErrorMessage(){
		$this->errorMessage = "";
		if($this->cookies->remove(self::$messageCookie)){
			return true;
		}
		return false;
	}*/
	
	public function didUserFillFormCorrectly(){
		$this->errorMessage = "";
		$correctUser = $this->isUserCorrect();
		$correctPassword = $this->isPasswordCorrect();
		if($correctUser === true && $correctPassword === true){
			return $this->getUserData();
			}
		$this->cookies->save(self::$messageCookie, $this->errorMessage);
		return false;
	}
	
	private function isUserCorrect(){
		if(strlen($_POST[self::$userID]) < 3){
			$this->errorMessage = self::$userToFewMessage;
		}
		elseif(strlen($_POST[self::$userID]) > 20){
			$this->errorMessage = self::$userToFewMessage;
		}
		else{
			return true;
		}
		$this->cookies->save(self::$messageCookie, $this->errorMessage);
		return false;
	}
	
	private function isPasswordCorrect(){
		if(strlen($_POST[self::$password1]) < 6){
			$passwordMessage = self::$passwordToFewMessage;
		}
		elseif(strlen($_POST[self::$password1]) > 20){
			$passwordMessage = self::$passwordToManyMessage;
		}
		elseif($_POST[self::$password1] != $_POST[self::$password1]){
			$passwordMessage = self::$noMatchMessage;
		}
		else{
			return true;
		}
		if($this->errorMessage === ""){
			$this->errorMessage = $passwordMessage;
		}
		else{
			$this->errorMessage .= "<br>" .  $passwordMessage; 
		}
		$this->cookies->save(self::$messageCookie, $this->errorMessage);
		return false;
	}
	
	private function getUserData(){
		$data = array("user" => $_POST[self::$userID], "pw" => $_POST[self::$password1]);
		if ($data != ""){
			return $data;
		}	
		return false;
	}
	
	private function setNewestErrorMessage($message){
		$userMessage = "";
		if($message != ""){
			$userMessage = $message;
		}
		elseif($this->errorMessage != ""){
			$userMessage = $this->errorMessage;	
		}
		else{
			$userMessage = $this->cookies->loadMessage(self::$messageCookie); 
		}
		//$this->cookies->save(self::$messageCookie, $userMessage);
		
		return $userMessage;
	}
	
	public function showCreateUser($message) {
		$this->errorMessage = $this->setNewestErrorMessage($message);
		//if($this->errorMessage != self::$outLoggedMessage){
		
		//}
		
		$ret = "<header>
					<h2>Ej inloggad<h2> 
				</header>
				<main>
					<article>
						<form method='post'>
							<p><a href='?'>Tillbaka</a></p>
						</form>
					</article>
					<article>
						<fieldset>
							<form method='post'>
							<legend>Registrera ny användare - Skriv in användarnamn och lösenord</legend>
							<p>$this->errorMessage</p> 
							<label for='UserID'>Användarnamn :</label>
							<input id='UserID' name='userID' type='text' value=$this->username>
							<label for='PasswordID1'>Lösenord :</label>
							<input id='PasswordID1' name='PasswordID1' type='password' value=''>
							<label for='PasswordID2'>Repetera lösenord :</label>
							<input id='PasswordID2' name='PasswordID2' type='password' value=''>
							<label for='Register'>Skicka: </label>
							<button type='submit'name='Register'>Registrera</button>
							</form>
						</fieldset>
					</article>
				</main>";
		
		return $ret;
		}
}