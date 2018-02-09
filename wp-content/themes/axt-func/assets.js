// Simple Email Validation
function validEmail(email) {
	var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
	var r = false;
	if (pattern.test(email)) {
		r = true;
	}
	return r;
}
// /Add

// Formatted Number
function formatStr(str) {
	str = String(str);
	str = str.replace(/(\.(.*))/g, '');
	var arr = str.split('');
	var str_temp = '';
	if (str.length > 3) {
		for (var i = arr.length - 1, j = 1; i >= 0; i--, j++) {
			str_temp = arr[i] + str_temp;
			if (j % 3 == 0) {
				str_temp = ' ' + str_temp;
			}
		}
		return str_temp;
	} else {
		return str;
	}
}