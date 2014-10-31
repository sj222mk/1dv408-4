<?php

namespace common;

class HTMLView{
	public function echoHTML($body, $footer){
		echo "
			<!DOCTYPE html>
			<meta charset=\"utf-8\">
			<html>
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