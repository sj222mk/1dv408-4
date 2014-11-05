<?php

namespace view;

class ServerStorage {
	private $address;
	private $time;
	private $agent;
	
	//constructor här??
	
	public function getUserServerSettings(){
		$this->address = $_SERVER['REMOTE_ADDR'];
		$this->agent = $_SERVER['HTTP_USER_AGENT'];
		$this->time = $_SERVER['REQUEST_TIME'];
		
		return array('agent' => $this->agent, 'address' => $this->address, 'time' => $this->time);
	}
}