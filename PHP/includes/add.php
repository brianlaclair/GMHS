<?php

// Some environmental things you shouldn't touch
$time 		= time();
$verified 	= false; // The post data is sus until proven otherwise

// Verify that the shared key makes sense - we have to test a range here as the packet might run into some speedbumps along the way to our server, and local computer times might be slightly off.
for($i = -5; $i < 5; $i++) {
	if (md5($secretKey . ($time + $i) . $_POST['name']) == $_SERVER['HTTP_AUTHORIZATION']) { 
		$verified = true;
	}
}

if ($verified) { 

	$aId 	= $_POST['aid'];
	$name 	= $_POST['name'];
	$score 	= $_POST['score'];
	
	$file = "scores/" . $aId . ".score";
	
	//Create the file if we need to
	if(!file_exists($file)) {
		$createhandle = fopen($file, "w");
		fclose($createhandle);
	}
	
	// Count the number of entries in the file already, and get the number that ours is
	$linecount = 0;
	$handle = fopen($file, "r") or die("Unable to open file!");
	while(!feof($handle)){
		$line = fgets($handle);
		$linecount++;
	}
	fclose($handle);
	$entryNumber = $linecount + 2;
	
	// Write the submission into the end of our score file
	$handle = fopen($file, "a") or die("Unable to open file!");
	$submission = $linecount . "|GMHS|" . $name . "|GMHS|" . $score . "\n";
	fwrite($handle, $submission);
	fclose($handle);
	
	echo "SUCCESS";

} else {
	echo "ERROR - Authentication Error.";
}

?>