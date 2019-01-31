var that = this;

this.base64ToBlob = function(base64Data, contentType) {
    contentType = contentType || '';
    var sliceSize = 1024;
    var byteCharacters = atob(base64Data);
    var bytesLength = byteCharacters.length;
    var slicesCount = Math.ceil(bytesLength / sliceSize);
    var byteArrays = new Array(slicesCount);

    for (var sliceIndex = 0; sliceIndex < slicesCount; ++sliceIndex) {
        var begin = sliceIndex * sliceSize;
        var end = Math.min(begin + sliceSize, bytesLength);

        var bytes = new Array(end - begin);
        for (var offset = begin, i = 0; offset < end; ++i, ++offset) {
            bytes[i] = byteCharacters[offset].charCodeAt(0);
        }
        byteArrays[sliceIndex] = new Uint8Array(bytes);
    }
    return new Blob(byteArrays, { type: contentType });
}

this.documentItemClick = function(id) {

	var token = this.CoreSitesProvider.currentSite.getToken();

	let wsPreSets = {
            wsToken: token,
            siteUrl: this.CoreSitesProvider.getCurrentSite().siteUrl,
            cleanUnicode: false,
            typeExpected: 'object',
            responseExpected: true
	};

	this.CoreWSProvider.call('report_parentprogressview_get_document', { 'id' : id }, wsPreSets).then(function(result) {
		//TODO pull mimetype
		debugger;
		
		console.log('Will open a document with type ' + result.mimetype);

		console.log('data:' + result.mimetype +';base64,' + result.document);

		that.CoreUtilsProvider.openInApp('data:' + result.mimetype +';base64,' + result.document);
	});
}
