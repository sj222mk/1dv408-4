<?php

namespace view;

class LogoutView {
	private $cookies;
	private $userMessage = "";
	private $username;
	private static $messageCookie = "Message";
	
	public function __construct(CookieStorage $cookies) {
		$this->cookies = $cookies;
	}

	public function setUserMessage($message){
		$this->userMessage = $message;
	}
	
	public function setUsername($name){
		$this->username = $name;
	}
	
	public function didUserPressLogout() {
		if (isset($_POST["logout"]))
			return true;
		
		return false;
	}
	
	
	public function showLogout() {
		$userMessage = $this->userMessage;
		//$this->cookies->save(self::$messageCookie, $this->userMessage);

		$ret = "<header>
					<h2>$this->username är inloggad<h2> 
				</header>
				<p>$this->userMessage</p>
				<form action='' method='post'>
				<input type='submit' value='Logga ut!' name='logout'/>
				</form>";
	
		return $ret;
		}
	
	private function setNewestMessage(){
		$textMessage = "";
		
		if($this->userMessage === ""){
			$this->userMessage = $this->cookies->loadMessage(self::$messageCookie);	
		}
		
		return $textMessage;
	}
}
