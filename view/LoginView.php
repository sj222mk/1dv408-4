<?php

namespace view;

class LoginView {
	private $cookies;
	private $errorMessage = "";
	private static $messageCookie = "Message";
	private $username = "";
	private static $userID = 'userID';
	private static $password = 'PasswordID'; 
	private static $outLoggedMessage = "Du har nu loggat ut";
	private static $register = "register";
	
	public function __construct(CookieStorage $cookies) {
		$this->cookies = $cookies;
	}
	
	public function didUserPressCreateNew(){
		if(key($_GET) === self::$register){
			return true;
		}
		return false;
	}

	public function didUserPressLogin() {
		
		if (isset($_POST["login"])){
			if($_POST[self::$userID] != ""){
				$this->username = $_POST[self::$userID];
			}
			if($_POST[self::$userID] != "" && $_POST[self::$password] != ""){
				return true;
			}
			else{
				if($_POST[self::$userID] === ""){
					$this->username = $_POST[self::$userID];
					$this->errorMessage = 'Användarnamn saknas';
				}
				else{
					$this->errorMessage = 'Lösenord saknas';
				}
			$this->cookies->save(self::$messageCookie, $this->errorMessage);
			}
		return false;
		}
	}
	
	public function getUserData(){
		$data = array("user" => $_POST[self::$userID], "pw" => $_POST[self::$password]);
		if ($data != ""){
			return $data;
		}	
		return false;
	}
	
	public function didUserWantToBeRemembered(){
	 	if(isset($_POST['AutologinID'])){
			return true;
		}
	 }
	 
	//Kontroll av mest aktuellt felmeddelande (vissa sätts av controllern, vissa av vyn - om de saknas kollas cookies!)
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
		
		return $userMessage;
	}
	
	public function showLogin($message) {
		$this->errorMessage = $this->setNewestErrorMessage($message);
		if($this->errorMessage != self::$outLoggedMessage){
			$this->cookies->save(self::$messageCookie, $this->errorMessage);
		}
		
		$ret = "<header>
					<h2>Ej inloggad<h2> 
				</header>
				<main>
					<article>
						<form method='post'>
							<p><a href='?register' >Registrera ny användare</a></p>
						</form>
					</article>
					<article>
						<fieldset>
							<form method='post'>
							<legend>Login - Skriv in användarnamn och lösenord</legend>
							<p>$this->errorMessage</p> 
							<label for='UserID'>Användarnamn :</label>
							<input id='UserID' name='userID' type='text' value=$this->username>
							<label for='PasswordID'>Lösenord :</label>
							<input id='PasswordID' name='PasswordID' type='password' value=''>
							<label for='AutologinID'>Håll mig inloggad :</label>
							<input id='AutologinID' name='AutologinID' type='checkbox'>
							<button type='submit'name='login'>Logga in</button>
							</form>
						</fieldset>
					</article>
				</main>";
		
		return $ret;
		}
}