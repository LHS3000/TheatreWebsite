<?php
function myconnect(){
	$host = 'perplexive.co.uk';
	$dbname = 'lhs8';
	$user = 'lhs8';
	$pwd = 'L3g3ndH4s1t';
	try {
		$conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pwd);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
	} catch (PDOException $e) {
		echo "PDOException: ".$e->getMessage();
	}
	return $conn;
}
?>
