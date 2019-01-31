
this.documentItemClick = function(id) {

	var token = this.CoreSitesProvider.currentSite.getToken();

	let wsPreSets = {
            wsToken: token,
            siteUrl: this.CoreSitesProvider.getCurrentSite().siteUrl,
            cleanUnicode: false,
            typeExpected: 'object',
            responseExpected: true
	};

	debugger;
	this.CoreWSProvider.call('report_parentprogressview_get_document', { 'id' : id }, wsPreSets).then(function(result) {
		debugger;
	});
}
