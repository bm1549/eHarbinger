<?php

include_once("classes.php");
include_once("session.php");
session_start();

//=============================================================

if (isset($_SESSION["completedPref"])){
	if (!$_SESSION["completedPref"]){
		//Create csv formatted description
		$prefCsv = "";
		
		if (isset($_POST["Preferences"])){
			foreach ($_POST["Preferences"] as $pref){
				if ($prefCsv == ""){
					// First item
					$prefCsv = $pref;
				}
				else{
					// Following item
					$prefCsv = $prefCsv . "," . $pref;
				}
			}
		}
		
		// Send profile data to database
		$username = $_SESSION["user"]->getName();
		$name = $_POST["name"];
		$location = $_POST["location"];
		$lang = $_POST["lang"];
		
		$_SESSION["user"]->conn->queryTrueFalse(
			"select updateprofile('$username','$name','$location','$lang','$prefCsv')"
		);
	}
}
header("Location: player.php");

	

?>