<?php

namespace controller;

require_once("./model/LoginModel.php");
require_once("./view/LoginView.php");
require_once("./view/LogoutView.php");
require_once("./view/CookieStorage.php"); 
require_once("./view/ServerStorage.php");
require_once("CreateUserController.php");

class LoginController {
	private $cookies;
	private $createUser;
	private $loginView;
	private $logoutView;
	private $model;
	private $servers;
	
	private $isLoggedIn;
	private $savedSession;
	private $sessionArray;
	private $textMessage = "";
	private $userData = "";
	private $userName;
	private $userServerSettings;
	private $userValid;
		
	private $showRegisterOption = true; //Ändra till false så försvinner alternativet att kunna registrera sig
		
	//Meddelanden till användaren efter validering och händelser
	private static $messageCookie = "Message";
	private static $outlogMessage = "Du har nu loggat ut";
	private static $cookieMessage = "Inloggning lyckades via cookies";
	private static $notCookieMessage = "Felaktig information i cookie";
	private static $notValidMessage = "Felaktigt användarnamn eller lösenord";
	private static $savedSessionMessage = "Inloggning lyckades och vi kommer ihåg dig nästa gång";
	private static $validUserMessage = "Inloggning lyckades";
	
	public function __construct() {		
		$this->model = new \model\LoginModel();
		$this->cookies = new \view\CookieStorage();
		$this->loginView = new \view\LoginView($this->cookies);
		$this->logoutView = new \view\LogoutView($this->cookies);
		$this->createUser = new CreateUserController();
		$this->servers = new \view\ServerStorage();
	}

	public function doLogin() {
		//Kolla av vem användaren är
		$this->userServerSettings = $this->servers->getUserServerSettings(); 
		
		//Kontrollera om användaren är inloggad
		if(!$this->checkIfUserIsLoggedIn()){
			$this->isLoggedIn = FALSE;
		}
		
		//Kolla om användaren vill registrera ny användare
		if($this->showRegisterOption === true && $this->loginView->didUserPressCreateNew() === true){
			return $this->createUser->doRegister();
		}
				
		while($this->isLoggedIn === FALSE){
		
		//Hantera indata
			if($this->checkIfUserPressedLogin() === true){
				//Validerar användaruppgifter	
				if($this->model->checkUser($this->userData) === true){
					if ($this->saveSessionAndSetMessage()){
						//Gå vidare till inloggad-sida
						return $this->doLogout();
					}
				}
				else{
					$this->textMessage = self::$notValidMessage;
					return $this->login();
				}
			}
			else{
				return $this->login();
			}
		}
		$this->isLoggedIn === TRUE;
		return $this->doLogout();
	}
		
	//Funktionaliteten på logga-ut-sidan
	private function doLogout(){
		
		while($this->isLoggedIn === TRUE){
			if ($this->logoutView->didUserPressLogout()){
				if($this->model->unsetSession() &&	
					$this->model->removeRememberedUserSession($this->userName) &&				
					$this->cookies->removeUserCookies())
					{
					$this->textMessage = self::$outlogMessage;
					$this->savedSession = false;
					$this->isLoggedIn = FALSE;	
				
					return $this->login();
				}						
			}
			return $this->logout();
		}
	}
	
	private function login(){
		if($this->textMessage != ""){
			$this->loginView->setUserMessage($this->textMessage);
		}
		if($this->userName != ""){
			$this->loginView->setUsername($this->userName);
		}
		$this->textMessage = "";
		return $this->loginView->showLogin();
	}
	
	private function logout(){
		$this->logoutView->setUsername($this->userName);
		$this->logoutView->setUserMessage($this->textMessage);
		$this->textMessage = "";
		return $this->logoutView->showLogout();
	}
	
	private function checkIfUserIsLoggedIn(){
		$clientName;
		$clientSession;		
												//Kollar om användarens serveruppgifter är lagrade i sessionen
		$clientName = $this->model->doesSessionExist($this->userServerSettings); //
		if($clientName != false){
			$this->userName = $clientName;	
			$this->isLoggedIn = TRUE;
			return true;
		}
			
		if($this->cookies->checkUserCookies() === true){ //Kollar kakor för användarnamn och lösenord är satta
			$userCookies = $this->cookies->loadUserFromCookie(); //Returns array eller false
			if($userCookies != false){
															//Kollar om sessionen finns sparad eller en äldre version
				$clientSession = $this->model->verifyRememberedClient($userCookies, $this->userServerSettings);
				if($clientSession === false){
					$this->textMessage = self::$notCookieMessage;
					return false;
				}
				if($clientSession != ""){
					$this->userName = $clientSession;
					$this->textMessage = self::$cookieMessage;
					$this->isLoggedIn = TRUE;
					return true;
				}
			}	
		}
		return false;
	}
	
	private function checkIfUserPressedLogin(){
		if ($this->loginView->didUserPressLogin()) {
			$this->userData = $this->loginView->getUserData();
			if($this->userData === false){
				$this->textMessage = self::notValidMessage;
				return false;
			}
			else{
				$this->userName = $this->userData['user'];
				return true;
			}
		}
	}
	
	private function saveSessionAndSetMessage(){
		if($this->model->saveUserSession($this->userData, $this->userServerSettings)){
			$this->isLoggedIn = TRUE;
			//Om användaren kryssat i "Håll mig inloggad"
			if($this->loginView->didUserWantToBeRemembered()){
				return $this->userWantsToBeRemembered();
			}
			else{//Om användaren inte vill bli hållen inloggad
				return $this->userDontWantToBeRemembered(); 
			}
		}
	}
	
	private function userWantsToBeRemembered(){
		$this->savedSession = TRUE;
		if($this->model->saveRememberedUserSession($this->userData, $this->userServerSettings) && 
			$this->cookies->saveUserCookies($this->userData))
			{
			$this->textMessage = self::$savedSessionMessage;
			return true;
		}
		else{											//Om sessionen inte lyckats sparas
			$this->textMessage = self::$validUserMessage;
			return true;
		}
	}	
	
	private function userDontWantToBeRemembered(){
		if($this->textMessage === ""){
			$this->savedSession = FALSE;
			$this->textMessage = self::$validUserMessage;
		}
		return true;
	}
}