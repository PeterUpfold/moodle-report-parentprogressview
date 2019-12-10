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


function ParentProgressViewModuleLinkHandler() {
	this.pattern = new RegExp('\/report\/parentprogressview\/');
	this.name = "ParentProgressViewModuleLinkHandler";
	this.priority = 1;
}

ParentProgressViewModuleLinkHandler.prototype = Object.create(that.CoreContentLinksHandlerBase.prototype);
ParentProgressViewModuleLinkHandler.prototype.constructor = ParentProgressViewModuleLinkHandler;
ParentProgressViewModuleLinkHandler.prototype.getActions = function(siteIds, url, params) {
	var action = {
		action: function() {
			console.log('Handling PPV link.');
			const modal = that.CoreDomUtilsProvider.showModalLoading();
			that.CoreAppProvider.getRootNavController().push('CoreSitePluginsPluginPage',
				{ 	component: 	'report_parentprogressview',
					method:		'mobile_documents_view',
					args:		[],
					jsData:		{},
					preSets:	{}

				}).then(function() {
					modal.dismiss();
				});
		}
	};
	return [action];
};

that.CoreContentLinksDelegate.registerHandler(new ParentProgressViewModuleLinkHandler())
console.log("TVS: Init called");
