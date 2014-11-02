<?php

namespace controller;

require_once("./model/CreateUserModel.php");
require_once("./view/CreateUserView.php");
require_once("./view/LoginView.php");
//require_once("./view/CookieStorage.php");

class CreateUserController {
	private $loginView;	
	private $model;
	private $cookies;
	private $userModel;
	private $createUserView;
	private $userMessage = "";
	private $doRegister = false;
	
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
		//Kollar om användaren vill registrera sig. 
		if($this->createUserView->didUserPressGoBack()){
			$this->userMessage = "";
			$this->cookies->remove(self::$messageCookie);
			$this->doRegister = false;
		}
		else{
			$this->doRegister = true;
		}
		
		while($this->doRegister === true){	
			if($this->createUserView->didUserPressRegister()){
				$userData = $this->createUserView->didUserFillFormCorrectly(); //Kontroll av ifyllda uppgifter
				if($userData != false){
					if($this->userModel->ifExists($userData) === false){ //Kontroll om användarnamnet är upptaget
						if($this->userModel->saveUser($userData)){
							$this->userMessage = self::$savedUserMessage; //Sätter meddelande att användaren har sparats
							$this->doRegister = false;
							return $this->loginView->showLogin($this->userMessage);
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
			return $this->createUserView->showCreateUser($this->userMessage);
		}
		
		return $this->loginView->showLogin($this->userMessage);
	}
}

		