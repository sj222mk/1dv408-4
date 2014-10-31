<?php

namespace view;

class LogoutView {
	private $cookies;
	private $userMessage = "";
	private static $messageCookie = "Message";
	
	public function __construct(CookieStorage $cookies) {
		$this->cookies = $cookies;
	}

	public function didUserPressLogout() {
		if (isset($_POST["logout"]))
			return true;
		
		return false;
	}
	
	
	public function showLogout($message, $userName) { //Sätt Admin som variabel!
		$userMessage = $this->setNewestMessage($message);
		$this->cookies->save(self::$messageCookie, $this->userMessage);
		//$this->cookies->remove(self::$messageCookie);

		$ret = "<header>
					<h2>$userName är inloggad<h2> 
				</header>
				<p>$userMessage</p>
				<form action='' method='post'>
				<input type='submit' value='Logga ut!' name='logout'/>
				</form>";
	
		return $ret;
		}
	
	private function setNewestMessage($message){
		$textMessage = "";
		
		if($message != ""){
			$textMessage = $message;
		}
		elseif($this->userMessage != ""){
			$textMessage = $this->userMessage;	
		}
		else{
			$textMessage = $this->cookies->loadMessage(self::$messageCookie); 
		}
		
		return $textMessage;
	}
}
