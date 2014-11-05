<?php

namespace common;

class HTMLView{
	public function echoHTML($body, $footer){
		echo "
			<!doctype html>
			<html lang='sv'>
			
				<head>
		        	<meta charset='utf-8'>
		        	<title>Inloggning</title>
				</head>
				
				<body>
					<header>
						<h1>Laborationskod sj222mk</h1>
					</header>
					<main>
						$body
					</main>
					<footer>
						$footer
					</footer>
				</body>
			</html>";
	}
}