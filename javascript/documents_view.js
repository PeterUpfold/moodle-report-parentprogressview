

var that = this;



this.documentItemClick = function(id) {

	let token = this.CoreSitesProvider.currentSite.getToken();
	let siteUrl = this.CoreSitesProvider.getCurrentSite().siteUrl;

	let wsPreSets = {
            wsToken: token,
            siteUrl: this.CoreSitesProvider.getCurrentSite().siteUrl,
            cleanUnicode: false,
            typeExpected: 'object',
            responseExpected: true
	};

	//let uri = siteUrl + '/report/parentprogressview/mobile/document.php?id=' + encodeURIComponent(id) + '&token=' + encodeURIComponent(token);
	let uri = siteUrl + '/report/parentprogressview/mobile/document/' + encodeURIComponent(id) + '/' + encodeURIComponent(token) + '/document.pdf';

	console.log(uri);
	/*this.CoreFileHelperProvider.downloadAndOpenFile(uri, 'report_parentprogressview', id).then(function(value) {
		alert('Promise fulfilled ' + JSON.stringify(value) );
	}, function(value) {
		alert('Promise failed ' + JSON.stringify(value));
	});*/
	
	/*
	this.CoreUtilsProvider.openInApp(uri).then(function(value) {
		//alert('Promise fulfilled ' + JSON.stringify(value) );
	}, function(value) {
		//alert('Promise failed ' + JSON.stringify(value));
	});
	*/

	this.CoreUtilsProvider.openOnlineFile(uri);

	//debugger;
	//this.CoreFileHelperProvider.utils.openFile(uri));
	//window.open(uri);
}

this.triggerDocumentsView = function(userid) {
	this.hideAllViews(userid);
	document.querySelector('ion-list.documents-list[data-pupil="' + userid + '"]').style.display = 'block';
}

this.triggerAchievementView = function(userid) {
	this.hideAllViews(userid);
	document.querySelector('ion-list.achievement[data-pupil="' + userid + '"]').style.display = 'block';

}

this.triggerBehaviourView = function(userid) {
	this.hideAllViews(userid);
	document.querySelector('ion-list.behaviour[data-pupil="' + userid + '"]').style.display = 'block';
}

this.triggerAttendanceView = function(userid) {
	this.hideAllViews(userid);
	document.querySelector('ion-list.attendance[data-pupil="' + userid + '"]').style.display = 'block';//
}

this.hideAllViews = function(userid) {
	//debugger;
	document.querySelector('ion-list.achievement[data-pupil="' + userid + '"]').style.display = 'none';
	document.querySelector('ion-list.documents-list[data-pupil="' + userid + '"]').style.display = 'none';
	document.querySelector('ion-list.behaviour[data-pupil="' + userid + '"]').style.display = 'none';
	document.querySelector('ion-list.attendance[data-pupil="' + userid + '"]').style.display = 'none';
}
