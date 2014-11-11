<?php

namespace controller;

require_once("./model/CreateUserModel.php");
require_once("./view/CreateUserView.php");
require_once("./view/LoginView.php");
require_once("./view/CookieStorage.php");

class CreateUserController {
	private $loginView;	
	private $model;
	private $cookies;
	private $userModel;
	private $createUserView;
	private $userMessage = "";
	private $doRegister = false;
	private $username = "";
	
	private static $messageCookie = "Message";
	private static $savedUserMessage = "Användaren är sparad";
	private static $notSavedMessage = "Användaren kunde inte sparas - vänligen försök igen";
	private static $allreadyTakenUsername = "Användarnamnet är redan upptaget";
	
	
	public function __construct() {
		$this->cookies = new \view\CookieStorage;
		$this->userModel = new \model\CreateUserModel();
		$this->createUserView = new \view\CreateUserView();
		$this->loginView = new \view\LoginView($this->cookies);
	}	
	
	public function doRegister(){
		$user;
		$userdata;
			
		//Kollar om användaren ångrar sig 
		$this->didUserRegret();
		
		//Själva registreringen av ny användare
		while($this->doRegister === true){	
			if($this->createUserView->didUserPressRegister()){
				$userData = $this->createUserView->didUserFillFormCorrectly(); //Kontroll av ifyllda uppgifter
				if($userData != false){
					if($this->userModel->ifExists($userData) === false){ //Kontroll om användarnamnet är upptaget
						$user = $this->userModel->saveUser($userData);
						if($user != false){
							$this->username = $user;
							$this->userMessage = self::$savedUserMessage; //Sätter meddelande att användaren har sparats
							$this->doRegister = false;
							return $this->login();
						}
						else{
							$this->userMessage = self::$notSavedMessage; //Sätter meddelande att användaren inte sparades
						}
					}
					else{
						$this->userMessage = self::$allreadyTakenUsername; //Sätter meddelande att användarnamnet är upptaget redan
					}		
				}
			}	
			return $this->show();
		}
		
		return $this->login();
	}
	
	private function show(){
		if($this->userMessage != ""){
			$this->createUserView->setUserMessage($this->userMessage);
		}
		if($this->username != ""){
			$this->createUserView->setUsername($this->userName);
		}
		$this->userMessage = "";
		return $this->createUserView->showCreateUser($this->userMessage);
	}
	
	private function login(){
		if($this->userMessage != ""){
			$this->loginView->setUserMessage($this->userMessage);
		}
		if($this->username != ""){
			$this->loginView->setUsername($this->username);
		}
		if($this->doRegister === false){
			header('location: ' . $_SERVER['PHP_SELF']);
		}
		return $this->loginView->showLogin();
	}
	
	private function didUserRegret(){
		if($this->createUserView->didUserPressGoBack()){
			$this->userMessage = "";
			$this->cookies->remove(self::$messageCookie);
			$this->doRegister = false;
		}
		else{
			$this->doRegister = true;
		}
	}
}

		