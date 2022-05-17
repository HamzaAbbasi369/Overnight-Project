function i18nInit() {
	var getDefaultLang = document.documentElement.lang;
	var lang = getQueryVar('lang');

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
}

var L = function (string) {
	return string.toLocaleString();
};

function localizeHTMLTag(tagId) {
	tag = document.getElementById(tagId);
	tag.innerHTML = L(tag.innerHTML);
}


