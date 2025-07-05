// Copyright 2022 3bra.com
// SPDX-License-Identifier: Apache-2.0

/** Globals **/

/** Helpers **/
function empty(v){
	// Handle most common cases first: undefined, null, empty string
	if(v === undefined || v === null || v === ''){
		return true;
	}
	
	// Handle arrays (very common in this app)
	if(Array.isArray(v) && !v.length){
		return true;
	}
	
	// Handle whitespace-only strings (common with user input)
	if(typeof v === 'string' && v.trim() === ''){
		return true;
	}
	
	// Handle empty objects (less common, more expensive check)
	if(typeof v === 'object' && Object.keys(v).length === 0){
		return true;
	}
	
	// Handle string literals (rare cases)
	if(v === 'undefined' || v === 'null'){
		return true;
	}
	
	// If none of the above, it's not empty
	return false;
}

function time_ago(input){
	const date = (input instanceof Date) ? input : new Date(input * 1000);
	const formatter = new Intl.RelativeTimeFormat('en');
	const ranges = {
		years: 3600 * 24 * 365,
		months: 3600 * 24 * 30,
		weeks: 3600 * 24 * 7,
		days: 3600 * 24,
		hours: 3600,
		minutes: 60,
		seconds: 1
	};
	const secondsElapsed = (date.getTime() - Date.now()) / 1000;
	for(let key in ranges){
		if(ranges[key] < Math.abs(secondsElapsed)){
			const delta = secondsElapsed / ranges[key];
			return formatter.format(Math.round(delta), key);
		}
	}
	// If no range matched (very recent), return "now"
	return 'now';
}

function timestamp_to_datetime(unixtimestamp){
	var d = new Date(unixtimestamp * 1000);
	return d.toLocaleDateString() +' '+ d.toLocaleTimeString();
}

/** Page **/
// Build page once jQuery is loaded AND the document is ready
$(document).ready(function(){

});