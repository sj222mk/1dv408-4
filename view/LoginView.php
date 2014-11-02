<?php

namespace view;

class LoginView {
	private $cookies;
	private $userMessage = "";
	private $showRegisterLink = true; 
	private static $messageCookie = "Message";
	private $username = "";
	private static $userID = 'userID';
	private static $password = 'PasswordID'; 
	private static $outLoggedMessage = "Du har nu loggat ut";
	private static $register = "register";
	
	public function __construct(CookieStorage $cookies) {
		$this->cookies = $cookies;
	}
	
	public function setShowRegisterLink(){
		$this->showRegisterLink = true;	
	}
	
	public function setUserMessage($message){
		$this->userMessage = $message;
	}
	
	public function setUsername($name){
		$this->username = $name;
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
					$this->userMessage = 'Användarnamn saknas';
				}
				else{
					$this->userMessage = 'Lösenord saknas';
				}
			$this->cookies->save(self::$messageCookie, $this->userMessage);
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
	 
	//Kontroll av mest aktuellt felmeddelande 
	private function setNewestUserMessage(){
		
		if($this->userMessage === ""){
			$this->userMessage = $this->cookies->loadMessage(self::$messageCookie);
		}
		
		if($this->userMessage != self::$outLoggedMessage){
			$this->cookies->save(self::$messageCookie, $this->userMessage);
		}
		
		return $this->userMessage;
	}
	
	public function showLogin() {
		$userMessage = $this->setNewestUserMessage();
		
		$registerLink = $this->showRegister();
				
		$ret = "<header>
					<h2>Ej inloggad<h2> 
				</header>
					$registerLink
					<article>
						<fieldset>
							<form method='post'>
							<legend>Login - Skriv in användarnamn och lösenord</legend>
							<p>$userMessage</p> 
							<label for='UserID'>Användarnamn :</label>
							<input id='UserID' name='userID' type='text' value=$this->username>
							<label for='PasswordID'>Lösenord :</label>
							<input id='PasswordID' name='PasswordID' type='password' value=''>
							<label for='AutologinID'>Håll mig inloggad :</label>
							<input id='AutologinID' name='AutologinID' type='checkbox'>
							<button type='submit'name='login'>Logga in</button>
							</form>
						</fieldset>
					</article>";
		
		return $ret;
	}

	private function showRegister(){
		if($this->showRegisterLink === true){
			$ret = "<article>
						<form method='post'>
							<p><a href='?register'>Registrera ny användare</a></p>
						</form>
					</article>";
			
			return $ret;
		}
		else{
			return "";
		}
	}
}