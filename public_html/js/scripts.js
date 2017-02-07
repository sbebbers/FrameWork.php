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

$(function(){
	console.log("Page ready.");
	$(".flash").fadeOut(2e3);
});