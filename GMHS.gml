function gmhsConfig(_baseurl, _fullpath, _appid) {
	
	global.gmhsBaseURL	= _baseurl;
	global.gmhsFullURL	= _fullpath;
	global.gmhsAppID	= _appid;
	
	global.gmhsScores	= array_create(0, 0);
	global.gmhsNames	= array_create(0, 0);

}

function gmhsSubmit(_name, _score, _secretkey){
	
	time = string(ue_time()); // Get the current unix time
	key = md5_string_utf8(string(_secretkey) + time + string(_name)) // Construct our key
	
	//Create DS Map to hold the HTTP Header info
	header = ds_map_create();

	//Add to the header DS Map
	ds_map_add(header,"Host", global.gmhsBaseURL);
	ds_map_add(header,"User-Agent", "GMHS");
	ds_map_add(header,"Content-Type", "application/x-www-form-urlencoded");
	ds_map_add(header,"Accept", "*/*");
	ds_map_add(header,"Authorization", key);
	
	request = http_request(global.gmhsFullURL, "POST", header, "action=add&aid=" + string(global.gmhsAppID) + "&name=" + string(_name) + "&score=" + string(_score));
	
	ds_map_destroy(header);
	
	return request;

}


function gmhsRequestScores(_number, _des) {

	// Handle optional parameters - if these aren't set, the rerturned data will be based on what you set in your server configuration
	_opt = "";
	
	if (!is_undefined(_number)) {
		_opt += "&num=" + string(_number);
	}
	
	if (!is_undefined(_des)) {
		_opt += "&hl=" + string(_des);
	}
	
	//Create DS Map to hold the HTTP Header info
	header = ds_map_create();

	//Add to the header DS Map
	ds_map_add(header,"Host", global.gmhsBaseURL);
	ds_map_add(header,"User-Agent", "GMHS");
	ds_map_add(header,"Accept", "*/*");
	
	_body = "?action=read&aid=" + string(global.gmhsAppID) + _opt;
	
	request = http_request(global.gmhsFullURL + _body, "GET", header, "");
	
	ds_map_destroy(header);
	
	return request;
	
}

function gmhsParseScores(_scoreData) {
	
	global.gmhsScores	= array_create(0, 0);
	global.gmhsNames	= array_create(0, 0);
	
	_linesArray = explode("</br>", _scoreData);
	
	for(i = 0; i < array_length(_linesArray); i++) {
		
		if (_linesArray[i] != "") {
			_thisLine = explode(" - ", _linesArray[i]);
			global.gmhsNames[i] = _thisLine[0];
			global.gmhsScores[i] = _thisLine[1];
		}
		
	}
}
		


/// @description  explode(delimiter,string)
/// @param delimiter
/// @param string

function explode(_delimiter, _string) {
	
	// Thank you to xot for this script to explode strings into arrays!
	// https://www.gmlscripts.com/script/explode
	
	arr = "";
	var del = _delimiter; 
	var str = _string + del;
	var len = string_length(del);
	var ind = 0;
	repeat (string_count(del, str)) {
	    var pos = string_pos(del, str) - 1;
	    arr[ind] = string_copy(str, 1, pos);
	    str = string_delete(str, 1, pos + len);
	    ind++;
	}
	return arr;
	
}


function ue_time(){

	// Thank you to xot for this script to get the current time since unix epoch
	// https://www.gmlscripts.com/script/unix_timestamp

	var timezone = date_get_timezone();
 
    date_set_timezone(timezone_utc);
 
    if (argument_count > 0) {
        var datetime = argument[0];
    } else {
        var datetime = date_current_datetime();
    }
 
    var timestamp = round(date_second_span(25569, datetime));
 
    date_set_timezone(timezone);
 
    return timestamp;

}