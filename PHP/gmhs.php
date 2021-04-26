<?php

// This file is the landing page for any requests - not much to change here.

// Include our configuration
include("config.php");

// Finds the action that's trying to be accomplished (either "read" or "add") - defaults to "read".
if (isset($_POST['action'])) {
	if ($_POST['action'] == "add") {
		$action = "add";
	} else {
		$action = "read";
	}
} else {
	$action = "read";
}

switch($action) {
	
	case "read":
		include("includes/read.php");
		break;
	
	case "add":
		include("includes/add.php");
		break;
}

?>