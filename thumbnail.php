<?php
/*
Parent Progress View, a module for Moodle to allow the viewing of documents and pupil data by authorised parents.
    Copyright (C) 2016-17 Test Valley School.

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


/**
 * The report_parentprogressview page which dumps the thumbnail of the specified document, if the user is duly authorized.
 *
 * @package report_parentprogressview
 * @author Test Valley School
 */



require(dirname(__FILE__).'/../../config.php');
require_once($CFG->libdir.'/adminlib.php');

require_login();
require_capability('report/parentprogressview:view', context_system::instance());

$id = required_param( 'id', PARAM_INT );

// get the document
try {
	$document_set = new report_parentprogressview\local\document_set( $USER );

	$document = $document_set->get_document_by_id( $id ); // only gets documents that this $USER has rights to

	if ( $document != null ) {

		header('Content-Type: image/jpeg');
		$output = fopen('php://output', 'w');
		
		fwrite($output, $document->get_thumbnail_bytes());

	}
	else {
		throw new Exception( get_string('nopermission', 'report_parentprogressview' ) );	
	}

}
catch (Exception $e) {
	print_error($e->getMessage());
}


