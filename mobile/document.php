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
 * This script presents a simple method for the mobile app to retrieve document
 * bits while presenting a Web Service token rather than a Moodle auth cookie.
 */

/**
 * AJAX_SCRIPT - exception will be converted into JSON.
 */
define('AJAX_SCRIPT', true);

/**
 * NO_MOODLE_COOKIES - we don't want any cookie.
 */

define('NO_MOODLE_COOKIES', true);

require_once('../../../config.php');

require_once($CFG->libdir . '/filelib.php');

require_once($CFG->dirroot . '/webservice/lib.php');

// Allow CORS requests.
//
header('Access-Control-Allow-Origin: *');

// cache control, robots,etc
header('X-Robots-Tag: noindex, nofollow');
header('Cache-Control: private');

// Authenticate the user.
$token = required_param('token', PARAM_ALPHANUM);
$id = required_param( 'id', PARAM_INT );

$webservicelib = new webservice();
$authenticationinfo = $webservicelib->authenticate_user($token);

require_capability('report/parentprogressview:view', context_system::instance());

// get the document
try {
	$document_set = new report_parentprogressview\local\document_set( $USER );

	$document = $document_set->get_document_by_id( $id ); // only gets documents that this $USER has rights to

	if ( $document != null ) {

		// record the document view as an event
		\report_parentprogressview\event\mobile_document_viewed::create_from_document($document)->trigger(); 

		header('Content-Type: ' . $document->sanitise_string_for_header($document->mimetype));
		$output = fopen('php://output', 'w');
		
		fwrite($output, $document->get_bytes());

	}
	else {
		throw new Exception( get_string('nopermission', 'report_parentprogressview' ) );	
	}

}
catch (Exception $e) {
	print_error($e->getMessage());
}

