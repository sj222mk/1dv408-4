<?php

namespace view;

class TimeView{
	private $time;
	
	function setTime(){
    setlocale(LC_ALL, "sv_SE");
    
   
	$day = $this->setDay(date('w'));
	$date = strftime('%#d');
	$month = $this->setMonth(date('m'));
	$year = strftime('%Y');
	$time = date('i:s');
	$Hour = date('H') + 1; //Pga differens lokalt o på servern?
	
	$ret = '<p>' . $day . ', den ' . $date .' ' . $month . ' år ' . $year . '. Klockan är [' .$Hour . ":" . $time . ']. </p>';
	return $ret;
	} 
	
	function setDay($d){
		switch($d){ 
        case '0': 
            $d = 'Söndag'; 
        break; 
        case '1': 
            $d = 'Måndag'; 
        break; 
        case '2': 
            $d = 'Tisdag'; 
			break;
		case '3': 
            $d = 'Onsdag'; 
        break; 
		case '4': 
            $d = 'Torsdag'; 
        break; 
		case '5': 
            $d = 'Fredag'; 
        break; 
		case '6': 
            $d = 'Lördag'; 
        break; 
    	} 
		
		return $d;
	}
	
	function setMonth($m){
		switch($m){ 
        case '1': 
            $m = 'Januari'; 
        break; 
        case '2': 
            $m = 'Februari'; 
        break; 
        case '3': 
            $m = 'Mars'; 
			break;
		case '4': 
            $m = 'April'; 
        break; 
		case '5': 
            $m = 'Maj'; 
        break; 
		case '6': 
            $m = 'Juni'; 
        break; 
		case '7': 
            $m = 'Juli'; 
        break; 
		case '8': 
            $m = 'Augusti'; 
			break;
		case '9': 
            $m = 'September'; 
        break; 
		case '10': 
            $m = 'Oktober'; 
        break; 
		case '11': 
            $m = 'November'; 
        break; 
		case '12': 
            $m = 'December'; 
        break; 
    	} 
		
		return $m;
	}
}
