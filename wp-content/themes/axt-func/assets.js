// Simple Email Validation
function vlidEmail(email) {
	var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
	var r = false;
	if(pattern.test(email)){
		r = true;
	}
	return r;
}