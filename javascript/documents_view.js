/*
Parent Progress View, a module for Moodle to allow the viewing of documents and pupil data by authorised parents.
    Copyright (C) 2016-20 Test Valley School.

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License,
    or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
var that = this;

document.querySelectorAll('ion-segment-button[value="documents"]').forEach(function(item) { item.click(); });

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
	// non-pretty URL above -- not functional for Android that wants to get mimetype from file extension
	
	let uri = siteUrl + '/report/parentprogressview/mobile/document/' + encodeURIComponent(id) + '/' + encodeURIComponent(token) + '/document.pdf';
	let fileObject = {
		"url": uri
	};

	this.CoreFileHelperProvider.downloadAndOpenFile(fileObject, 'report_parentprogressview', id).then(function(value) {
		console.log('Fulfilled');
	}, function(value) {
		alert('Failed to open the document. Please check that you are still connected to the Internet.');
		console.log(JSON.stringify(value));
	});
	

}

this.timetableItemClick = function(id) {

	let token = this.CoreSitesProvider.currentSite.getToken();
	let siteUrl = this.CoreSitesProvider.getCurrentSite().siteUrl;

	let wsPreSets = {
            wsToken: token,
            siteUrl: this.CoreSitesProvider.getCurrentSite().siteUrl,
            cleanUnicode: false,
            typeExpected: 'object',
            responseExpected: true
	};

	//let uri = siteUrl + '/report/parentprogressview/mobile/timetable.php?id=' + encodeURIComponent(id) + '&token=' + encodeURIComponent(token);
	// non-pretty URL above -- not functional for Android that wants to get mimetype from file extension
	
	let uri = siteUrl + '/report/parentprogressview/mobile/timetable/' + encodeURIComponent(id) + '/' + encodeURIComponent(token) + '/timetable.html';
	let fileObject = {
		"url": uri
	};

	this.CoreFileHelperProvider.downloadAndOpenFile(fileObject, 'report_parentprogressview', id).then(function(value) {
	}, function(value) {
		alert('Failed to open the timetable. Please check that you are still connected to the Internet.');
		console.log(JSON.stringify(value));
	});
	

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
	document.querySelector('ion-list.achievement[data-pupil="' + userid + '"]').style.display = 'none';
	document.querySelector('ion-list.documents-list[data-pupil="' + userid + '"]').style.display = 'none';
	document.querySelector('ion-list.behaviour[data-pupil="' + userid + '"]').style.display = 'none';
	document.querySelector('ion-list.attendance[data-pupil="' + userid + '"]').style.display = 'none';
}
