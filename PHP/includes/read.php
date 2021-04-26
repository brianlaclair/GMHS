<?php
// Gets the app ID number from the request - this must be set, but is only useful if you have more than one game using this system
$aId = $_GET['aid'];

// If the GET variable exists, determine if we're sorting High to Low or not - Defaults based on Config
if (isset($_GET['hl'])) {
	if ($_GET['hl'] == "true" || $_GET['hl'] == "1") {
		$hl = true;
	} else {
		$hl = false;
	}
}

// Find the number of results the system wants - Defaults based on Config
if (isset($_GET['num']) && is_numeric($_GET['num'])) {
	$howMany = $_GET['num'];
} else {
	$howMany = $defaultScoresToShow;
}

// If the file exists...
if (file_exists("scores/" . $aId . ".score")) {
	
	// Open the file and add each line to an array
	$scoreFile = fopen("scores/" . $aId . ".score", "r") or die("Unable to open file!");

	$lineArray 		= [];
	while(!feof($scoreFile)) {
		
		$lineArray[count($lineArray)] = fgets($scoreFile);
		
	}

	fclose($scoreFile);

	// We're going to split the scores into two arrays, the key for each value references the other array's key's (i.e.:
	// Brian's score is 12, so - scoreArray["5" => "12"]
	// and nameArray["5" => "Brian"]
	$scoreArray 	= [];
	$nameArray 		= [];
	for($i = 0; $i <= count($lineArray); $i++) { 
		
		if ($lineArray[$i] != "") {
			
			$thisline = explode("|GMHS|", $lineArray[$i]);
			$scoreArray += [$thisline[0] => $thisline[2]];
			$nameArray += [$thisline[0] => $thisline[1]];
			
		}
		
	}

	// Decide which direction we're sorting in, and do the sort
	if ($hl) {
		arsort($scoreArray, SORT_NUMERIC);
	} else {
		asort($scoreArray, SORT_NUMERIC);
	}

	// Copy only the part of the scores we want to display
	$finalScoreArray = array_slice($scoreArray, 0, $howMany, true);

	// Run through the final array and print it out to the screen
	foreach ($finalScoreArray as $key => $val) {
		echo "$nameArray[$key] - $val</br>";
	}
	
} else {
	
	echo "ERROR - No highscores for this app yet";

}
?>