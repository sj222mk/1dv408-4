<?php

namespace controller;

require_once("./model/CreateUserModel.php");
require_once("./view/CreateUserView.php");

class CreateUserController {
	private $model;
	private $cookies;
	private $createUserView;
	private $userMessage = "";
	private $doRegister = false;
	
	private static $savedUserMessage = "Användaren är sparad";
	private static $notSavedMessage = "Användaren kunde inte sparas - vänligen försök igen";
	private static $allreadyTakenUsername = "Användarnamnet är redan upptaget";
	
	
	public function __construct() {
		$this->cookies = new \view\CookieStorage;
		$this->userModel = new \model\CreateUserModel();
		$this->createUserView = new \view\CreateUserView($this->cookies);
	}	
	
	public function doRegister(){
		$this->doRegister = true;
		while($this->doRegister === true){	
			if($this->createUserView->didUserPressRegister()){
				$userData = $this->createUserView->didUserFillFormCorrectly();
				if($userData != false){
					if($this->userModel->ifExists($userData) === false){
						if($this->userModel->saveUser($userData)){
							$this->userMessage = self::$savedUserMessage;
							//gå ur loop här!
						}
						else{
							$this->userMessage = self::$notSavedMessage;
						}
					}
					else{
						$this->userMessage = self::$allreadyTakenUsername;
						//Användarnamnet upptaget - meddela
					}
						
						//doRegister = false;
						
					
				}
			}	
			return $this->createUserView->showCreateUser($this->userMessage);
		}
		return false;
	}
}

		