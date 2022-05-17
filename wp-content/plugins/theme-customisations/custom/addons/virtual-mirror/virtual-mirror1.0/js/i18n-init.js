function i18nInit() {
	function getQueryVal(key) {
		let query = window.location.search.substring(1);
		let keyVal = query.split('&');
		for (let i = 0; i < keyVal.length; i++) {
			let pair = keyVal[i].split('=');
			if (pair[0] == key) {
				return pair[1];
			}
		}
		return (false);
	}
	var getDefaultLang = document.documentElement.lang;
	var lang = getQueryVal('lang');

	if (lang != '') {
		String.locale = lang;
	} else {
		String.locale = getDefaultLang;
		document.title = L(document.title);
	}

	localizeHTMLTag('vm-header');
	localizeHTMLTag('usephoto');
	localizeHTMLTag('loadphoto');
	localizeHTMLTag('deletephoto');
	localizeHTMLTag('usevideo');
	localizeHTMLTag('stopvideo');
	localizeHTMLTag('savesnap');
	localizeHTMLTag('filterbyshape');
}

var L = function(string) {
	return string.toLocaleString();
};

function localizeHTMLTag(tagId) {
	tag = document.getElementById(tagId);
	tag.innerHTML = L(tag.innerHTML);
}


