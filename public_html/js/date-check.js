/**
 * Checks for a valid date,assuming dates between 1900 and 2100
 * i.e., will validate any leap year between these dates, assigning
 * the 29th day of February correctly. For use with web forms where
 * the date picker plug ins are not necessary or do not work well
 * on mobile devices.
 * Where the day sent exceeds the maximum number of days in that
 * month, the last legal day of that month is set in the return
 * object
 * 
 * @param	int, int, int
 * @author	sbebbington
 * @date	23 Jan 2017 - 14:18:12
 * @version	0.0.1a
 * @return	object
 * @todo
 */
function checkDate(_d, _m, _y){
	_d = parseInt(_d), _m = parseInt(_m), _y = parseInt(_y);
	if(_m < 1 || _m > 12){
		return {"error":"Month range must be between 1 and 12"};
	}
	if(_d < 0){
		return {"error":"Day range cannot be negative"};
	}
	if(_y >= 0 && _y < 100){
		_y += 2000;
	}else if(_y >= 100 && _y < 1900){
		return {"error":"Year range must be between 1900 and the current year in the Gregorian calendar"};
	}else if(_y < 0 || _y > 2100){
		return {"error":"Year out of range - "+_y};
	}
	_m--;
	maxDays = Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	if(_m == 1 && _y%4 == 0 && (_y > 1900 && _y < 2100)){
		maxDays[1] = 29;
	}
	if(_d > maxDays[_m]){
		_d = maxDays[_m];
	}
	_m++;
	if(_d < 10){
		_d = "0" + _d;
	}
	if(_m < 10){
		_m = "0" + _m;
	}
	return {"day": ""+_d, "month": ""+_m, "year": ""+_y};
}

/**
 * Returns the month (1 - 12) from string
 * 
 * @param	string
 * @author	sbebbington
 * @date	7 Jul 2017 - 09:23:12
 * @version	0.0.1
 * @return	object
 * @todo
 */
function getNumericMonth(_m){
	_ms = {
		"Jan": 1, "Feb": 2, "Mar": 3, "Apr": 4, "May": 5, "Jun" :6,
		"Jul": 7, "Aug": 8, "Sep": 9, "Oct": 10, "Nov": 11, "Dec": 12
	};
	return _ms[_m.substr(0, 3)];
}

/**
 * Returns the month as its noun
 * either short (3-digit) or full
 * as specified by the second
 * parameter (false being short,
 * true being full)
 * 
 * @param	int, Boolean
 * @author	sbebbington
 * @date	7 Jul 2017 - 09:30:37
 * @version	0.0.1
 * @return	string
 * @todo
 */
function getMonthName(_m, _f){
	_ms = {
		1: "January", 2: "February", 3: "March", 4: "April",
		5: "May", 6: "June", 7: "July", 8: "August", 9: "September",
		10: "October", 11: "November", 12: "December"
	};
	if(_f == false){
		return _ms[_m].substr(0, 3);
	}
	return _ms[_m];
}

/**
 * Specific page-load events for date-picker
 */
$(function(){
	$("#day, #month, #year").on("change", function(){
		_d = $("#day").val(),_m = $("#month").val(),_y = $("#year").val(), _f = null;
		if("NaN" === parseInt(_m)){
			_f = Boolean(_m.length > 3);
			_m = getNumericMonth(_m);
		}
		_v = checkDate(_d, _m, _y);
		/* Changes day to valid day if not correct */
		$("#day").val(_v.day);
	})
});