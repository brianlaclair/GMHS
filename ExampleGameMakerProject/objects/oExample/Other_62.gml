if (async_load[? "id"] == submitr) {
	
	show_debug_message(async_load[? "result"]);

}

if (async_load[? "id"] == requests) {
	
	// Parses the HS list recieved
	gmhsParseScores(async_load[? "result"]);

}