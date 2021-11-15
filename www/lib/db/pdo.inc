<?php
	$pdo = new PDO("mysql:host=localhost;dbname=hansungmall", "root", "hansung!@#");
	// 1. 에러 출력하지 않음
	//$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
	//  2. Warning만 출력
	//$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	// 3. 에러 출력
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>